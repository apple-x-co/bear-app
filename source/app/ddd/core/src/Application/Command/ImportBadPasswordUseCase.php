<?php

declare(strict_types=1);

namespace AppCore\Application\Command;

use AppCore\Infrastructure\Query\BadPasswordCommandInterface;
use GuzzleHttp\ClientInterface;
use Throwable;

use function array_merge;
use function array_walk;
use function explode;

use const PHP_EOL;

final class ImportBadPasswordUseCase
{
    private const array URLS = [
        'https://raw.githubusercontent.com/danielmiessler/SecLists/refs/heads/master/Passwords/Common-Credentials/2020-200_most_used_passwords.txt',
        'https://raw.githubusercontent.com/danielmiessler/SecLists/refs/heads/master/Passwords/Common-Credentials/2023-200_most_used_passwords.txt',
        'https://raw.githubusercontent.com/danielmiessler/SecLists/refs/heads/master/Passwords/Common-Credentials/2024-197_most_used_passwords.txt',
        'https://raw.githubusercontent.com/danielmiessler/SecLists/refs/heads/master/Passwords/Common-Credentials/500-worst-passwords.txt',
    ];

    public function __construct(
        private readonly ClientInterface $client,
        private readonly BadPasswordCommandInterface $badPasswordCommand,
    ) {
    }

    /** @SuppressWarnings("PHPMD.EmptyCatchBlock") */
    public function execute(): ImportBadPasswordOutputData
    {
        $passwords = [];

        $urls = self::URLS;
        array_walk(
            $urls,
            function (string $item) use (&$passwords): void {
                $response = $this->client->request('GET', $item);
                $content = $response->getBody()->getContents();

                $array = explode(PHP_EOL, $content);
                foreach ($array as $password) {
                    if ($password === '') {
                        continue;
                    }

                    try {
                        $this->badPasswordCommand->add($password);
                    } catch (Throwable) {
                    }
                }

                $passwords = array_merge($passwords, $array);
            },
        );

        return new ImportBadPasswordOutputData($passwords);
    }
}
