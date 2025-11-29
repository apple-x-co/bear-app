<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use DateTimeInterface;
use Ray\MediaQuery\Annotation\DbQuery;

interface EmailQueueCommandInterface
{
    /**
     * @param positive-int     $active
     * @param non-negative-int $attempts
     * @param non-negative-int $maxAttempts
     *
     * @return array{id: positive-int}
     *
     * @SuppressWarnings("PHPMD.ExcessiveParameterList")
     */
    #[DbQuery('email_queues/email_queue_add', 'row')]
    public function add(
        string $senderEmailAddress,
        string|null $senderName,
        string $subject,
        string $text,
        string|null $html,
        int $active,
        int $attempts,
        int $maxAttempts,
        DateTimeInterface $scheduleDate,
        DateTimeInterface|null $createdDate = null,
    ): array;

    /**
     * @param positive-int     $id
     * @param non-negative-int $attempts
     */
    #[DbQuery('email_queues/email_queue_update_sent')]
    public function sent(int $id, int $attempts, DateTimeInterface $sentDate): void;

    /**
     * @param positive-int     $id
     * @param non-negative-int $attempts
     */
    #[DbQuery('email_queues/email_queue_update_attempts')]
    public function updateAttempts(int $id, int $attempts): void;

    /**
     * @param positive-int     $id
     * @param non-positive-int $active
     */
    #[DbQuery('email_queues/email_queue_update_active')]
    public function updateActive(int $id, int $active): void;

    #[DbQuery('email_queues/email_queue_delete_by_sent_older', 'row')]
    public function deleteBySentOlder(DateTimeInterface $sentDate): void;
}
