<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Openapi;

use App\Infrastructure\Http\AppController\AppController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class GetOpenapiDocHttpAdapter extends AppController
{
    /**
     * @Route("/docs", methods={"GET"})
     */
    public function __invoke(OpenapiSchema $openapiSchema): Response
    {
        return new JsonResponse($openapiSchema->asJson(), 200, [], true);
    }
}
