<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use AppCore\Infrastructure\Entity\VerificationCodeEntity;
use AppCore\Infrastructure\Entity\VerificationCodeEntityFactory;
use Ray\MediaQuery\Annotation\DbQuery;

interface VerificationCodeQueryInterface
{
    #[DbQuery('verification_codes/verification_code_by_uuid', type: 'row', factory: VerificationCodeEntityFactory::class)]
    public function itemByUuid(string $uuid): VerificationCodeEntity|null;
}
