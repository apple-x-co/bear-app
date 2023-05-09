<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Shared;

use AppCore\Domain\Mail\Address;
use AppCore\Domain\Mail\Email;
use AppCore\Domain\Mail\FileRender;
use AppCore\Domain\Mail\InvalidArgumentException;
use AppCore\Domain\Mail\TransportInterface;
use PHPMailer\PHPMailer\PHPMailer;
use Ray\Di\Di\Named;

use function is_string;

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
        $templateId = $email->getTemplateId();
        if (! is_string($templateId)) {
            throw new InvalidArgumentException('templateId');
        }

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
            $mailer->addReplyTo($cc->getEmail(), $cc->getName() ?? '');
        }

        foreach ($email->getBcc() as $bcc) {
            $mailer->addReplyTo($bcc->getEmail(), $bcc->getName() ?? '');
        }

        $subject = $this->subjectDir . DIRECTORY_SEPARATOR . $templateId . '.txt';
        $mailer->Subject = (new FileRender())($subject, $email->getTemplateVars()); // phpcs:ignore

        $format = $email->getEmailFormat();
        if ($format->isHtml()) {
            $mailer->isHTML();
            $text = $this->textDir . DIRECTORY_SEPARATOR . $templateId . '.txt';
            $html = $this->htmlDir . DIRECTORY_SEPARATOR . $templateId . '.html';
            $mailer->Body = (new FileRender())($html, $email->getTemplateVars()); // phpcs:ignore
            $mailer->AltBody = (new FileRender())($text, $email->getTemplateVars()); // phpcs:ignore
            $mailer->send();

            return;
        }

        $mailer->isHTML(false);
        $text = $this->textDir . DIRECTORY_SEPARATOR . $templateId . '.txt';
        $mailer->Body = (new FileRender())($text, $email->getTemplateVars()); // phpcs:ignore
        $mailer->AltBody = ''; // phpcs:ignore
        $mailer->send();
    }
}
