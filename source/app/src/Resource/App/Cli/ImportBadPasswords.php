<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\App\Cli;

use AppCore\Infrastructure\Query\BadPasswordCommandInterface;
use BEAR\Resource\ResourceObject;
use GuzzleHttp\ClientInterface;
use Throwable;

use function explode;

use const PHP_EOL;

/**
 * php ./bin/cli.php post app://self/cli/import-bad-passwords
 */
class ImportBadPasswords extends ResourceObject
{
    private const URL = 'https://raw.githubusercontent.com/danielmiessler/SecLists/master/Passwords/500-worst-passwords.txt';

    public function __construct(
        private readonly ClientInterface $client,
        private readonly BadPasswordCommandInterface $badPasswordCommand,
    ) {
    }

    /** @SuppressWarnings(PHPMD.EmptyCatchBlock) */
    public function onPost(): static
    {
        $response = $this->client->request('GET', self::URL);
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

        $this->body['passwords'] = $array;

        return $this;
    }
}
