<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Openapi;

use cebe\openapi\Reader;
use cebe\openapi\ReferenceContext;
use cebe\openapi\spec\OpenApi;

final class OpenapiSchema
{
    /**
     * @var string
     */
    private $pathToOas;

    public function __construct(string $pathToOasSpec)
    {
        $this->pathToOas = $pathToOasSpec;
    }

    public function asJson(): string
    {
        $cebeOpenapi = $this->asCebeOpenapi();
        /** @var array $specData */
        $specData = $cebeOpenapi->getSerializableData();

        /** @var string $json */
        $json = \json_encode($specData);

        return $json;
    }

    public function asCebeOpenapi(): OpenApi
    {
        /** @var OpenApi $cebeOpenapi */
        $cebeOpenapi = Reader::readFromYamlFile($this->pathToOas);
        $cebeOpenapi->resolveReferences(new ReferenceContext($cebeOpenapi, '/'));

        return $cebeOpenapi;
    }
}
