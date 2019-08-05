<?php

namespace App\Common;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class AppController extends AbstractController
{
    protected function successJson(array $data = [], $status = 200, array $headers = [], array $context = []): JsonResponse
    {
        return $this->json([
            'data' => $data,
        ], $status, $headers, $context);
    }

    protected function errorJson(string $message, $status = 400, array $headers = [], array $context = []): JsonResponse
    {
        return $this->json([
            'error' => $message,
        ], $status, $headers, $context);
    }

    protected function flush()
    {
        $this->getDoctrine()->getManager()->flush();
    }
}
