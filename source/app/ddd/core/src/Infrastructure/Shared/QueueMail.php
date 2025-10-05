<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Shared;

use AppCore\Attribute\EmailDir;
use AppCore\Domain\Mail\Email;
use AppCore\Domain\Mail\InvalidArgumentException;
use AppCore\Domain\Mail\RecipientType;
use AppCore\Domain\Mail\TemplateNotFoundException;
use AppCore\Domain\Mail\TemplateRendererInterface;
use AppCore\Domain\Mail\TransportInterface;
use AppCore\Infrastructure\Query\EmailQueueCommandInterface;
use AppCore\Infrastructure\Query\EmailQueueRecipientCommandInterface;

use function is_readable;
use function is_string;

use const DIRECTORY_SEPARATOR;

readonly class QueueMail implements TransportInterface
{
    private const string SUBJECT_DIR_NAME = 'subject';
    private const string TEXT_DIR_NAME = 'text';
    private const string HTML_DIR_NAME = 'html';
    private const string PLAIN_TEXT_EXT = '.txt';
    private const string HTML_EXT = '.html';
    private const int ACTIVE = 1;
    private const int DEFAULT_INIT_ATTEMPTS = 0;
    private const int DEFAULT_MAX_ATTEMPTS = 5;

    /** @SuppressWarnings(PHPMD.LongVariable) */
    public function __construct(
        private EmailQueueCommandInterface $emailQueueCommand,
        #[EmailDir]
        private string $emailDir,
        private EmailQueueRecipientCommandInterface $emailQueueRecipientCommand,
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

        $scheduleDate = $email->getScheduleDate();
        if ($scheduleDate === null) {
            throw new InvalidArgumentException('"ScheduleDate" must be not null');
        }

        $subjectDir = $this->emailDir . DIRECTORY_SEPARATOR . self::SUBJECT_DIR_NAME . DIRECTORY_SEPARATOR;
        $textDir = $this->emailDir . DIRECTORY_SEPARATOR . self::TEXT_DIR_NAME . DIRECTORY_SEPARATOR;
        $htmlDir = $this->emailDir . DIRECTORY_SEPARATOR . self::HTML_DIR_NAME . DIRECTORY_SEPARATOR;

        $subject = $this->renderTemplate(
            $subjectDir . $templateId . self::PLAIN_TEXT_EXT,
            $email->getTemplateVars(),
        );
        $text = $this->renderTemplate(
            $textDir . $templateId . self::PLAIN_TEXT_EXT,
            $email->getTemplateVars(),
        );
        $format = $email->getEmailFormat();
        $html = $format->isHtml() ? $this->renderTemplate(
            $htmlDir . $templateId . self::HTML_EXT,
            $email->getTemplateVars(),
        ) : null;

        $array = $this->emailQueueCommand->add(
            $from->getEmail(),
            $from->getName(),
            $subject,
            $text,
            $html,
            self::ACTIVE,
            self::DEFAULT_INIT_ATTEMPTS,
            self::DEFAULT_MAX_ATTEMPTS,
            $scheduleDate,
        );
        $emailQueueId = $array['id'];

        foreach ($email->getTo() as $to) {
            $this->emailQueueRecipientCommand->add(
                $emailQueueId,
                RecipientType::To->value,
                $to->getEmail(),
                $to->getName(),
            );
        }

        foreach ($email->getReplayTo() as $replyTo) {
            $this->emailQueueRecipientCommand->add(
                $emailQueueId,
                RecipientType::ReplayTo->value,
                $replyTo->getEmail(),
                $replyTo->getName(),
            );
        }

        foreach ($email->getCc() as $cc) {
            $this->emailQueueRecipientCommand->add(
                $emailQueueId,
                RecipientType::Cc->value,
                $cc->getEmail(),
                $cc->getName(),
            );
        }

        foreach ($email->getBcc() as $bcc) {
            $this->emailQueueRecipientCommand->add(
                $emailQueueId,
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
