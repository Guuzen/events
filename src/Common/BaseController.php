<?php

namespace App\Common;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class BaseController extends AbstractController
{
    protected function jsonSuccess(array $data = [], int $status = 200, array $headers = []): JsonResponse
    {
        return new JsonResponse(['data' => $data], $status, $headers);
    }

    protected function jsonError(string $message = '', int $status = 400, array $headers = []): JsonResponse
    {
        return new JsonResponse(['error' => $message], $status, $headers);
    }
}
