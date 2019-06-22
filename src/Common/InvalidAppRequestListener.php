<?php
declare(strict_types=1);

namespace App\Common;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\Validator\ConstraintViolationInterface;

class InvalidAppRequestListener
{

    public function onKernelException(GetResponseForExceptionEvent $event): void
    {
        /** @var InvalidAppRequest $exception */
        $exception = $event->getException();

        if ($exception instanceof InvalidAppRequest) {
            $errors = array_map(function (ConstraintViolationInterface $error) {
                return [
                    'field' => $error->getPropertyPath(),
                    'message' => $error->getMessage(),
                ];
            }, iterator_to_array($exception->errors()));

            $response = new JsonResponse([
                'errors' => $errors,
            ], 400);
            $event->setResponse($response);
        }
    }
}
