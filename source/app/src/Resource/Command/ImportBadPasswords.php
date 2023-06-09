<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\Command;

use AppCore\Infrastructure\Query\BadPasswordCommandInterface;
use BEAR\Resource\ResourceObject;
use GuzzleHttp\ClientInterface;
use Ray\AuraSqlModule\Annotation\Transactional;
use Throwable;

use function explode;

use const PHP_EOL;

class ImportBadPasswords extends ResourceObject
{
    private const URL = 'https://raw.githubusercontent.com/danielmiessler/SecLists/master/Passwords/500-worst-passwords.txt';

    public function __construct(
        private readonly ClientInterface $client,
        private readonly BadPasswordCommandInterface $badPasswordCommand,
    ) {
    }

    /**
     * php ./bin/command.php post /import-bad-password
     */
    public function onPost(): static
    {
        $this->body['passwords'] = $this->execute();

        return $this;
    }

    /**
     * @return array<string>
     *
     * @Transactional()
     * @SuppressWarnings(PHPMD.EmptyCatchBlock)
     */
    protected function execute(): array
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

        return $array;
    }
}
