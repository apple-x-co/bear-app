<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\Command;

use AppCore\Infrastructure\Query\AdminDeleteCommandInterface;
use AppCore\Infrastructure\Query\AdminDeleteQueryInterface;
use BEAR\Resource\ResourceObject;
use DateTimeImmutable;
use Ray\AuraSqlModule\Annotation\Transactional;

class DeleteAdmins extends ResourceObject
{
    public function __construct(
        private readonly AdminDeleteCommandInterface $adminDeleteCommand,
        private readonly AdminDeleteQueryInterface $adminDeleteQuery,
    ) {
    }

    /**
     * php ./bin/command.php post /delete-admins
     */
    public function onPost(): static
    {
        $adminDeletes = $this->adminDeleteQuery->list();
        foreach ($adminDeletes as $adminDelete) {
            $this->execute($adminDelete['admin_id']);
        }

        return $this;
    }

    /** @Transactional() */
    protected function execute(int $adminId): void
    {
        $this->adminDeleteCommand->delete($adminId, new DateTimeImmutable());

        // 関連するファイルがあればこのタイミングで削除する
    }
}
