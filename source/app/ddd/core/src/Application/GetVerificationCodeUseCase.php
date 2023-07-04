<?php

declare(strict_types=1);

namespace AppCore\Application;

use AppCore\Domain\VerificationCode\VerificationCodeNotFoundException;
use AppCore\Infrastructure\Query\VerificationCodeQueryInterface;

class GetVerificationCodeUseCase
{
    /** @SuppressWarnings(PHPMD.LongVariable) */
    public function __construct(
        private readonly VerificationCodeQueryInterface $verificationCodeQuery,
    ) {
    }

    public function execute(GetVerificationCodeInputData $inputData): GetVerificationCodeOutputData
    {
        $verificationCode = $this->verificationCodeQuery->itemByUuid($inputData->uuid);
        if ($verificationCode === null) {
            throw new VerificationCodeNotFoundException();
        }

        return new GetVerificationCodeOutputData(
            $verificationCode->id,
            $verificationCode->uuid,
            $verificationCode->url,
        );
    }
}
