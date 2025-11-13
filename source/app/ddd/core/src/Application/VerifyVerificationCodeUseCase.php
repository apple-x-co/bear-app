<?php

declare(strict_types=1);

namespace AppCore\Application;

use AppCore\Domain\VerificationCode\VerificationCodeNotFoundException;
use AppCore\Infrastructure\Query\VerificationCodeCommandInterface;
use AppCore\Infrastructure\Query\VerificationCodeQueryInterface;
use DateTimeImmutable;

class VerifyVerificationCodeUseCase
{
    /** @SuppressWarnings("PHPMD.LongVariable") */
    public function __construct(
        private readonly VerificationCodeCommandInterface $verificationCodeCommand,
        private readonly VerificationCodeQueryInterface $verificationCodeQuery,
    ) {
    }

    public function execute(VerifyVerificationCodeInputData $inputData): VerifyVerificationCodeOutputData
    {
        $verificationCode = $this->verificationCodeQuery->itemByUuid($inputData->uuid);
        if ($verificationCode === null || $verificationCode->code !== $inputData->code) {
            throw new VerificationCodeNotFoundException();
        }

        $this->verificationCodeCommand->verified($verificationCode->id, new DateTimeImmutable());

        return new VerifyVerificationCodeOutputData($verificationCode->url);
    }
}
