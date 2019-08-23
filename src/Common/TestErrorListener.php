<?php

namespace App\Common;

use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class TestErrorListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getException();

        /** @var Exception $exception */
        $response = new JsonResponse([
            'class'   => get_class($exception),
            'message' => $exception->getMessage(),
            'file'    => $exception->getFile(),
            'line'    => $exception->getLine(),
            'code'    => $exception->getCode(),
            'trace'   => array_slice($exception->getTrace(), 0, 2),
        ], 500);
        $response->setEncodingOptions(JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        $event->setResponse($response);
    }
}
