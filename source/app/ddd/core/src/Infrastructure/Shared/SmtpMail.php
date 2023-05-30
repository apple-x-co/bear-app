<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Shared;

use AppCore\Domain\Mail\Address;
use AppCore\Domain\Mail\Email;
use AppCore\Domain\Mail\TemplateNotFoundException;
use AppCore\Domain\Mail\TemplateRenderer;
use AppCore\Domain\Mail\TransportInterface;
use PHPMailer\PHPMailer\PHPMailer;
use Ray\Di\Di\Named;

use function is_readable;

use const DIRECTORY_SEPARATOR;

class SmtpMail implements TransportInterface
{
    public function __construct(
        private readonly PHPMailer $mailer,
        #[Named('email_html_dir')] private readonly string $htmlDir,
        #[Named('email_subject_dir')] private readonly string $subjectDir,
        #[Named('email_text_dir')] private readonly string $textDir,
    ) {
    }

    public function send(Email $email): void
    {
        $mailer = $this->mailer;
        $mailer->clearAllRecipients();

        $from = $email->getFrom();
        if ($from instanceof Address) {
            $mailer->setFrom($from->getEmail(), $from->getName() ?? '');
        }

        foreach ($email->getTo() as $to) {
            $mailer->addAddress($to->getEmail(), $to->getName() ?? '');
        }

        foreach ($email->getReplayTo() as $replyTo) {
            $mailer->addReplyTo($replyTo->getEmail(), $replyTo->getName() ?? '');
        }

        foreach ($email->getCc() as $cc) {
            $mailer->addCC($cc->getEmail(), $cc->getName() ?? '');
        }

        foreach ($email->getBcc() as $bcc) {
            $mailer->addBCC($bcc->getEmail(), $bcc->getName() ?? '');
        }

        $format = $email->getEmailFormat();
        $subject = $email->getSubject();
        $text = $email->getText();
        $html = $email->getHtml();

        $templateId = $email->getTemplateId();
        if ($templateId !== null) {
            $subject = $this->renderTemplate(
                $this->subjectDir . DIRECTORY_SEPARATOR . $templateId . '.txt',
                $email->getTemplateVars(),
            );
            $text = $this->renderTemplate(
                $this->textDir . DIRECTORY_SEPARATOR . $templateId . '.txt',
                $email->getTemplateVars(),
            );
            $html = $format->isHtml() ? $this->renderTemplate(
                $this->htmlDir . DIRECTORY_SEPARATOR . $templateId . '.html',
                $email->getTemplateVars(),
            ) : null;
        }

        if ($format->isHtml()) {
            $mailer->isHTML();
            $mailer->Subject = $subject ?? ''; // phpcs:ignore
            $mailer->Body = $html ?? ''; // phpcs:ignore
            $mailer->AltBody = $text ?? ''; // phpcs:ignore
            $mailer->send();

            return;
        }

        $mailer->isHTML(false);
        $mailer->Subject = $subject ?? ''; // phpcs:ignore
        $mailer->Body = $text ?? ''; // phpcs:ignore
        $mailer->AltBody = ''; // phpcs:ignore
        $mailer->send();
    }

    /**
     * @param array<string, mixed> $vars
     */
    private function renderTemplate(string $filePath, array $vars = []): string
    {
        if (! is_readable($filePath)) {
            throw new TemplateNotFoundException($filePath);
        }

        return (new TemplateRenderer())($filePath, $vars); // phpcs:ignore
    }
}
