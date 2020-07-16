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
    private $pathToOasSpec;

    public function __construct(string $pathToOasSpec)
    {
        $this->pathToOasSpec = $pathToOasSpec;
    }

    public function asJson(): string
    {
        $cebeOpenapi = $this->asCebeOpenapi();
        /** @var array $specData */
        $specData = $cebeOpenapi->getSerializableData();

        return \json_encode($specData);
    }

    public function asCebeOpenapi(): OpenApi
    {
        $cebeOpenapi = Reader::readFromYamlFile($this->pathToOasSpec);
        $cebeOpenapi->resolveReferences(new ReferenceContext($cebeOpenapi, '/'));

        return $cebeOpenapi;
    }
}
