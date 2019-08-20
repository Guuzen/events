<?php

namespace App\Tests\Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

use Codeception\Module;
use Codeception\Module\REST;
use Coduo\PHPMatcher\PHPUnit\PHPMatcherAssertions;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\PropertyNormalizer;
use Symfony\Component\Serializer\Serializer;

class Api extends Module
{
    use PHPMatcherAssertions;

    private const ID_PATH = '$.data.id';

    /** @var Serializer $serializer */
    private $serializer;

    /** @var REST $restModule */
    private $restModule;

    public function _initialize(): void
    {
        $this->restModule = $this->getModule('REST');
        $this->serializer = new Serializer(
            [
                new ArrayDenormalizer(),
                new PropertyNormalizer(
                    null,
                    new CamelCaseToSnakeCaseNameConverter()
                ),
            ],
            [new JsonEncoder()]
        );
    }

    public function seeResponseContainsId(): void
    {
        $this->restModule->seeResponseMatchesJsonType(['string:uuid'], self::ID_PATH);
    }

    public function grabIdFromResponse(): string
    {
        return $this->restModule->grabDataFromResponseByJsonPath(self::ID_PATH)[0];
    }

    public function seeResponseContainsJson($pattern = null): void
    {
        $pattern = [
            'data' => $this->serializer->normalize($pattern, 'json'),
        ];
        $pattern = $this->serializer->encode($pattern, 'json');

        $this->assertMatchesPattern($pattern, $this->restModule->grabResponse());
    }

    public function sendPOST(string $url, $params = [], $files = []): void
    {
        $params = $this->serializer->normalize($params, 'json'); // TODO do i need json?

        $this->restModule->sendPOST($url, $params, $files);
    }
}
