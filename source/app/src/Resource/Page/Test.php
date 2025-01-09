<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\Page;

use AppCore\Domain\Test\Test as TestDomain;
use AppCore\Domain\Test\TestRepositoryInterface;
use BEAR\Resource\ResourceObject;
use Koriym\HttpConstants\CacheControl;
use Koriym\HttpConstants\ResponseHeader;

use function time;

class Test extends ResourceObject
{
    /** @var array<string, string> */
    public $headers = [ResponseHeader::CACHE_CONTROL => CacheControl::PUBLIC_ . ',max-age=300'];

    public function __construct(
        private readonly TestRepositoryInterface $testRepository
    ) {
    }

    /** @return static */
    public function onGet(): static
    {
        $test = $this->testRepository->findById('001');
        $this->body['test'] = $test;

        $tests = $this->testRepository->findAll();
        $this->body['tests'] = $tests;

        $pagination = $this->testRepository->pagination();
        $this->body['pagination'] = $pagination;

        // FIXME: 追加後データをどうやって識別するか? (last_insert_id??)
        $this->testRepository->store(
            new TestDomain(
                (string) time(),
                'test3'
            ),
        );

        return $this;
    }
}
