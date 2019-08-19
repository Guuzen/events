<?php

namespace App\Tests\Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

use Codeception\Module;
use Codeception\Module\REST;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\PropertyNormalizer;
use Symfony\Component\Serializer\Serializer;

class Api extends Module
{
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

    public function seeResponseContainsJson($json = null): void
    {
        $json = [
            'data' => $this->serializer->normalize($json, 'json'),
        ];

        $this->restModule->seeResponseContainsJson($json);
    }

    public function sendPOST(string $url, $params = [], $files = []): void
    {
        $params = $this->serializer->normalize($params, 'json');

        $this->restModule->sendPOST($url, $params, $files);
    }
}
