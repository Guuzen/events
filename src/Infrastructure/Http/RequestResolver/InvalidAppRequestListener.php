<?php

namespace App\Infrastructure\Http\RequestResolver;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Validator\ConstraintViolationInterface;
use function array_map;
use function iterator_to_array;

class InvalidAppRequestListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof InvalidAppRequest) {
            /** @var InvalidAppRequest $exception */
            $errors = array_map(
                function (ConstraintViolationInterface $error) {
                    return [
                        'field'   => $error->getPropertyPath(),
                        'message' => $error->getMessage(),
                    ];
                }, iterator_to_array($exception->errors())
            );

            $response = new JsonResponse(
                [
                    'errors' => $errors,
                ], 400
            );
            $event->setResponse($response);
        }
    }
}
