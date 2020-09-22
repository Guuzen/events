<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Openapi;

use cebe\openapi\Reader;
use cebe\openapi\ReferenceContext;
use cebe\openapi\spec\OpenApi;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

final class OpenapiSchema
{
    /**
     * @var string
     */
    private $pathToOas;

    /**
     * @var CacheInterface
     */
    private $cache;

    public function __construct(string $pathToOasSpec, CacheInterface $cache)
    {
        $this->pathToOas = $pathToOasSpec;
        $this->cache     = $cache;
    }

    public function asJson(): string
    {
        /** @var string $json */
        $json = $this->cache->get(
            'app.openapi_schema_json',
            function (ItemInterface $item) {
                $cebeOpenapi = $this->asCebeOpenapi();
                /** @var array $specData */
                $specData = $cebeOpenapi->getSerializableData();

                return \json_encode($specData);
            }
        );

        return $json;
    }

    public function asCebeOpenapi(): OpenApi
    {
        /** @var OpenApi $cebeOpenapi */
        $cebeOpenapi = $this->cache->get(
            'app.openapi_schema',
            function (ItemInterface $item) {
                /** @var OpenApi $cebeOpenapi */
                $cebeOpenapi = Reader::readFromYamlFile($this->pathToOas);
                $cebeOpenapi->resolveReferences(new ReferenceContext($cebeOpenapi, '/'));

                return $cebeOpenapi;
            }
        );

        return $cebeOpenapi;
    }
}
