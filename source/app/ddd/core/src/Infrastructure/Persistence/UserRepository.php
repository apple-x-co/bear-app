<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Persistence;

use AppCore\Domain\User\User;
use AppCore\Domain\User\UserNotFoundException;
use AppCore\Domain\User\UserRepositoryInterface;
use AppCore\Infrastructure\Entity\UserEntity;
use AppCore\Infrastructure\Query\UserCommandInterface;
use AppCore\Infrastructure\Query\UserQueryInterface;
use DateTimeImmutable;

use function array_map;
use function assert;

final readonly class UserRepository implements UserRepositoryInterface
{
    public function __construct(
        private UserCommandInterface $userCommand,
        private UserQueryInterface $userQuery,
    ) {
    }

    public function findById(int $id): User
    {
        $userEntity = $this->userQuery->item($id);
        if ($userEntity === null) {
            throw new UserNotFoundException('User not found');
        }

        return $this->entityToModel($userEntity);
    }

    /**
     * @param list<positive-int> $ids
     *
     * @return list<User>
     */
    public function findByIds(array $ids): array
    {
        $userEntityList = $this->userQuery->listByIds($ids);
        if (empty($userEntityList)) {
            return [];
        }

        return array_map(
            fn (UserEntity $item) => $this->entityToModel($item),
            $userEntityList,
        );
    }

    public function findByUsername(string $username): User
    {
        $userEntity = $this->userQuery->itemByUsername($username);
        if ($userEntity === null) {
            throw new UserNotFoundException('User not found');
        }

        return $this->entityToModel($userEntity);
    }

    /** @inheritDoc */
    public function findByExpired(DateTimeImmutable $dateTime): array
    {
        $userEntityList = $this->userQuery->listByExpired($dateTime);
        if (empty($userEntityList)) {
            return [];
        }

        return array_map(
            fn (UserEntity $item) => $this->entityToModel($item),
            $userEntityList,
        );
    }

    /** @SuppressWarnings("PHPMD.StaticAccess") */
    private function entityToModel(UserEntity $userEntity): User
    {
        return User::reconstruct(
            $userEntity->id,
            $userEntity->uid,
            $userEntity->username,
            $userEntity->password,
            $userEntity->active,
            $userEntity->signupDate,
            $userEntity->leavedDate,
            $userEntity->purgeDate,
            $userEntity->lastLoggedInDate,
            $userEntity->createdDate,
            $userEntity->updatedDate,
        );
    }

    public function store(User $user): void
    {
        $user->id === null ? $this->insert($user) : $this->update($user);
    }

    private function insert(User $user): void
    {
        $array = $this->userCommand->add(
            $user->uid,
            $user->username,
            $user->password,
            $user->active ? 1 : 0,
            $user->signupDate,
            $user->leavedDate,
            $user->purgeDate,
            $user->lastLoggedInDate,
            $user->createdDate,
            $user->updatedDate,
        );

        $userId = $array['id'];
        $user->setNewId($userId);
    }

    private function update(User $user): void
    {
        assert($user->id !== null);

        $this->userCommand->update(
            $user->id,
            $user->username,
            $user->password,
            $user->active ? 1 : 0,
            $user->signupDate,
            $user->leavedDate,
            $user->purgeDate,
            $user->lastLoggedInDate,
            $user->updatedDate,
        );
    }

    public function delete(User $user): void
    {
        assert($user->id !== null);

        $this->userCommand->delete($user->id);
    }
}
