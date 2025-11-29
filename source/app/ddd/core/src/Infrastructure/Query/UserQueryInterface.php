<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use AppCore\Infrastructure\Entity\UserEntity;
use AppCore\Infrastructure\Entity\UserEntityFactory;
use DateTimeInterface;
use Ray\MediaQuery\Annotation\DbQuery;

interface UserQueryInterface
{
    #[DbQuery('users/user_item', type: 'row', factory: UserEntityFactory::class)]
    public function item(int $id): UserEntity|null;

    #[DbQuery('users/user_item_by_username', type: 'row', factory: UserEntityFactory::class)]
    public function itemByUsername(string $username): UserEntity|null;

    /**
     * @param list<positive-int> $ids
     *
     * @return list<UserEntity>
     */
    #[DbQuery('users/user_list_by_ids', factory: UserEntityFactory::class)]
    public function listByIds(array $ids): array;

    /** @return list<UserEntity> */
    #[DbQuery('users/user_list_by_expired', factory: UserEntityFactory::class)]
    public function listByExpired(DateTimeInterface $dateTime): array;
}
