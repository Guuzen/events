<?php

declare(strict_types=1);

namespace App;

use App\Infrastructure\Http\AppController;
use cebe\openapi\Reader;
use cebe\openapi\ReferenceContext;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class GetOpenapiDocHttpAdapter extends AppController
{
    /**
     * @Route("/docs", methods={"GET"})
     */
    public function __invoke(): Response
    {
        $yamlFile         = '/var/www/html/openapi/stoplight.yaml';
        $spec   = Reader::readFromYamlFile($yamlFile);
        $spec->resolveReferences(new ReferenceContext($spec, "/"));

        /** @var array $specData */
        $specData = $spec->getSerializableData();

        $specJson = \json_encode($specData, JSON_PRETTY_PRINT);

        return new JsonResponse($specJson, 200, [], true);
    }
}
