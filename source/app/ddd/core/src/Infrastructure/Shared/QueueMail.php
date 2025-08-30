<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Shared;

use AppCore\Domain\Mail\Email;
use AppCore\Domain\Mail\EmailDir;
use AppCore\Domain\Mail\InvalidArgumentException;
use AppCore\Domain\Mail\RecipientType;
use AppCore\Domain\Mail\TemplateNotFoundException;
use AppCore\Domain\Mail\TemplateRendererInterface;
use AppCore\Domain\Mail\TransportInterface;
use AppCore\Infrastructure\Query\EmailCommandInterface;
use AppCore\Infrastructure\Query\EmailRecipientCommandInterface;

use function is_readable;
use function is_string;

use const DIRECTORY_SEPARATOR;

readonly class QueueMail implements TransportInterface
{
    /** @SuppressWarnings(PHPMD.LongVariable) */
    public function __construct(
        private EmailCommandInterface $emailCommand,
        #[EmailDir]
        private string $emailDir,
        private EmailRecipientCommandInterface $emailRecipientCommand,
        private TemplateRendererInterface $templateRenderer,
    ) {
    }

    /** @SuppressWarnings(PHPMD.NPathComplexity) */
    public function send(Email $email): void
    {
        $templateId = $email->getTemplateId();
        if (! is_string($templateId)) {
            throw new InvalidArgumentException('"TemplateId" must be not null');
        }

        $from = $email->getFrom();
        if ($from === null) {
            throw new InvalidArgumentException('"From" must be not null');
        }

        $scheduleAt = $email->getScheduleDate();
        if ($scheduleAt === null) {
            throw new InvalidArgumentException('"ScheduleAt" must be not null');
        }

        $subjectDir = $this->emailDir . DIRECTORY_SEPARATOR . 'subject' . DIRECTORY_SEPARATOR;
        $textDir = $this->emailDir . DIRECTORY_SEPARATOR . 'text' . DIRECTORY_SEPARATOR;
        $htmlDir = $this->emailDir . DIRECTORY_SEPARATOR . 'html' . DIRECTORY_SEPARATOR;

        $subject = $this->renderTemplate(
            $subjectDir . $templateId . '.txt',
            $email->getTemplateVars(),
        );
        $text = $this->renderTemplate(
            $textDir . $templateId . '.txt',
            $email->getTemplateVars(),
        );
        $format = $email->getEmailFormat();
        $html = $format->isHtml() ? $this->renderTemplate(
            $htmlDir . $templateId . '.html',
            $email->getTemplateVars(),
        ) : null;

        $array = $this->emailCommand->add(
            $from->getEmail(),
            $from->getName(),
            $subject,
            $text,
            $html,
            $scheduleAt,
        );
        $emailId = $array['id'];

        foreach ($email->getTo() as $to) {
            $this->emailRecipientCommand->add(
                $emailId,
                RecipientType::To->value,
                $to->getEmail(),
                $to->getName(),
            );
        }

        foreach ($email->getReplayTo() as $replyTo) {
            $this->emailRecipientCommand->add(
                $emailId,
                RecipientType::ReplayTo->value,
                $replyTo->getEmail(),
                $replyTo->getName(),
            );
        }

        foreach ($email->getCc() as $cc) {
            $this->emailRecipientCommand->add(
                $emailId,
                RecipientType::Cc->value,
                $cc->getEmail(),
                $cc->getName(),
            );
        }

        foreach ($email->getBcc() as $bcc) {
            $this->emailRecipientCommand->add(
                $emailId,
                RecipientType::Bcc->value,
                $bcc->getEmail(),
                $bcc->getName(),
            );
        }
    }

    /** @param array<string, mixed> $vars */
    private function renderTemplate(string $filePath, array $vars = []): string
    {
        if (! is_readable($filePath)) {
            throw new TemplateNotFoundException($filePath);
        }

        return ($this->templateRenderer)($filePath, $vars); // phpcs:ignore
    }
}
