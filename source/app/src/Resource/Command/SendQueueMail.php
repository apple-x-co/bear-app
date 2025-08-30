<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\Command;

use AppCore\Domain\Mail\Address;
use AppCore\Domain\Mail\Email;
use AppCore\Domain\Mail\Format;
use AppCore\Domain\Mail\RecipientType;
use AppCore\Domain\Mail\TransportInterface;
use AppCore\Infrastructure\Query\EmailCommandInterface;
use AppCore\Infrastructure\Query\EmailQueryInterface;
use AppCore\Infrastructure\Query\EmailRecipientQueryInterface;
use BEAR\Resource\ResourceObject;
use DateTimeImmutable;
use Ray\Di\Di\Named;

use function array_merge;
use function array_reduce;

class SendQueueMail extends ResourceObject
{
    public function __construct(
        private readonly EmailCommandInterface $emailCommand,
        private readonly EmailQueryInterface $emailQuery,
        private readonly EmailRecipientQueryInterface $emailRecipientQuery,
        #[Named('SMTP')]
        private readonly TransportInterface $transport,
    ) {
    }

    /**
     * php ./bin/command.php post /send-queue-mail
     */
    public function onPost(): static
    {
        $queues = $this->emailQuery->list();
        $ids = array_reduce(
            $queues,
            static function (array $carry, array $item) {
                $carry[] = $item['id'];

                return $carry;
            },
            [],
        );

        if (empty($ids)) {
            return $this;
        }

        $recipients = $this->emailRecipientQuery->list($ids);
        $recipientMap = array_reduce(
            $recipients,
            static function (array $carry, array $item) {
                $carry[$item['email_id']][] = $item;

                return $carry;
            },
            [],
        );

        foreach ($queues as $queue) {
            if (! isset($recipientMap[$queue['id']])) {
                continue;
            }

            $email = (new Email())
                ->setFrom(new Address($queue['sender_email_address'], $queue['sender_name']))
                ->setSubject($queue['subject'])
                ->setText($queue['text'])
                ->setHtml($queue['html'])
                ->setEmailFormat($queue['html'] === null ? Format::Text : Format::Both);

            foreach ($recipientMap[$queue['id']] as $recipient) {
                $address = new Address($recipient['recipient_email_address'], $recipient['recipient_name']);
                $email = match ($recipient['recipient_type']) {
                    RecipientType::To->value => $email->setTo(
                        array_merge($email->getTo(), [$address]),
                    ),
                    RecipientType::Cc->value => $email->setCc(
                        array_merge($email->getCc(), [$address]),
                    ),
                    RecipientType::Bcc->value => $email->setBcc(
                        array_merge($email->getBcc(), [$address]),
                    ),
                    RecipientType::ReplayTo->value => $email->setReplayTo(
                        array_merge($email->getReplayTo(), [$address]),
                    ),
                    default => $email,
                };
            }

            $this->transport->send($email);

            $this->emailCommand->sent($queue['id'], new DateTimeImmutable());
        }

        return $this;
    }
}
