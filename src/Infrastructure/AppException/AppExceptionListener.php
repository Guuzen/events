<?php

declare(strict_types=1);

namespace App\Infrastructure\AppException;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

final class AppExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof AppException) {
            $message = $exception->getMessage();
            if ($message === '') {
                $message = $this->stringifyException($exception);
            }
            $response = new JsonResponse([
                'error' => $message,
            ], 400);
            $event->setResponse($response);
        }
    }

    private function stringifyException(AppException $exception): string
    {
        $className = (new \ReflectionClass($exception))->getShortName();
        $message   = \preg_replace('/[A-Z]/', ' \\0', \lcfirst($className));
        if (null === $message) {
            throw new \RuntimeException('Cant stringify exception', 0, $exception);
        }

        return \strtolower($message);
    }
}
