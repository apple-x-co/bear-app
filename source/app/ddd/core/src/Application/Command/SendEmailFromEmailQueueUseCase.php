<?php

declare(strict_types=1);

namespace AppCore\Application\Command;

use AppCore\Domain\LoggerInterface;
use AppCore\Domain\Mail\Address;
use AppCore\Domain\Mail\Email;
use AppCore\Domain\Mail\Format;
use AppCore\Domain\Mail\RecipientType;
use AppCore\Domain\Mail\TransportInterface;
use AppCore\Infrastructure\Entity\EmailQueueEntity;
use AppCore\Infrastructure\Entity\EmailQueueRecipientEntity;
use AppCore\Infrastructure\Query\EmailQueueCommandInterface;
use AppCore\Infrastructure\Query\EmailQueueQueryInterface;
use AppCore\Infrastructure\Query\EmailQueueRecipientCommandInterface;
use AppCore\Infrastructure\Query\EmailQueueRecipientQueryInterface;
use DateInterval;
use DateTimeImmutable;
use Ray\Di\Di\Named;
use Throwable;

use function array_reduce;
use function array_values;

/** @SuppressWarnings(PHPMD.CouplingBetweenObjects) */
final readonly class SendEmailFromEmailQueueUseCase
{
    private const int INACTIVE = 0;

    /** @SuppressWarnings(PHPMD.LongVariable) */
    public function __construct(
        private EmailQueueCommandInterface $emailQueueCommand,
        private EmailQueueRecipientCommandInterface $emailQueueRecipientCommand,
        private EmailQueueQueryInterface $emailQueueQuery,
        private EmailQueueRecipientQueryInterface $emailQueueRecipientQuery,
        #[Named('command')]
        private LoggerInterface $logger,
        #[Named('SMTP')]
        private TransportInterface $transport,
    ) {
    }

    /**
     * @SuppressWarnings(PHPMD.LongVariable)
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function execute(): void
    {
        $this->deleteOldQueue();

        [$emailQueueList, $emailQueueRecipientMap] = $this->getSendableEmailQueue();
        if (empty($emailQueueList) || empty($emailQueueRecipientMap)) {
            return;
        }

        foreach ($emailQueueList as $emailQueue) {
            if (! isset($emailQueueRecipientMap[$emailQueue->id])) {
                continue;
            }

            $email = $this->makeEmailWithQueue($emailQueue, $emailQueueRecipientMap[$emailQueue->id]);

            $attempts = $emailQueue->attempts + 1;

            try {
                $this->transport->send($email);
                $this->emailQueueCommand->sent($emailQueue->id, $attempts, new DateTimeImmutable());

                continue;
            } catch (Throwable $throwable) {
                $this->logger->log((string) $throwable);
            }

            $this->emailQueueCommand->updateAttempts($emailQueue->id, $attempts);

            if ($emailQueue->maxAttempts > $attempts) {
                continue;
            }

            $this->emailQueueCommand->updateActive($emailQueue->id, self::INACTIVE);
        }
    }

    private function deleteOldQueue(): void
    {
        $now = new DateTimeImmutable();
        $interval = new DateInterval('P30D');
        $interval->invert = 1;
        $thirtyDaysAgo = $now->add($interval);

        try {
            $this->emailQueueRecipientCommand->deleteBySentOlder($thirtyDaysAgo);
            $this->emailQueueCommand->deleteBySentOlder($thirtyDaysAgo);
        } catch (Throwable $throwable) {
            $this->logger->log((string) $throwable);
        }
    }

    /**
     * @return array{0: list<EmailQueueEntity>, 1: array<positive-int, list<EmailQueueRecipientEntity>>}
     *
     * @SuppressWarnings(PHPMD.LongVariable)
     */
    private function getSendableEmailQueue(): array
    {
        $now = new DateTimeImmutable();

        $emailQueueList = $this->emailQueueQuery->listBySendable($now);
        if (empty($emailQueueList)) {
            return [[], []];
        }

        $emailQueueId = array_values(
            array_reduce(
                $emailQueueList,
                static function (array $carry, EmailQueueEntity $item) {
                    $carry[] = $item->id;

                    return $carry;
                },
                [],
            ),
        );

        $emailQueueRecipientList = $this->emailQueueRecipientQuery->listByEmailQueueIds($emailQueueId);
        if (empty($emailQueueRecipientList)) {
            return [[], []];
        }

        $emailQueueRecipientMap = array_reduce(
            $emailQueueRecipientList,
            static function (array $carry, EmailQueueRecipientEntity $item) {
                $carry[$item->emailQueueId][] = $item;

                return $carry;
            },
            [],
        );

        return [$emailQueueList, $emailQueueRecipientMap];
    }

    /**
     * @param list<EmailQueueRecipientEntity> $emailQueueRecipientList
     *
     * @SuppressWarnings(PHPMD.LongVariable)
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    private function makeEmailWithQueue(EmailQueueEntity $emailQueue, array $emailQueueRecipientList): Email
    {
        $email = (new Email())
            ->setFrom(new Address($emailQueue->senderMailAddress, $emailQueue->senderName))
            ->setSubject($emailQueue->subject)
            ->setText($emailQueue->text)
            ->setHtml($emailQueue->html)
            ->setEmailFormat($emailQueue->html === null ? Format::Text : Format::Both);

        foreach ($emailQueueRecipientList as $emailQueueRecipient) {
            $address = new Address(
                $emailQueueRecipient->recipientEmailAddress,
                $emailQueueRecipient->recipientName,
            );
            $recipientType = RecipientType::from($emailQueueRecipient->recipientType);
            $email = match ($recipientType) {
                RecipientType::To => $email->addTo($address),
                RecipientType::Cc => $email->addCc($address),
                RecipientType::Bcc => $email->addBcc($address),
                RecipientType::ReplayTo => $email->addReplayTo($address),
            };
        }

        return $email;
    }
}
