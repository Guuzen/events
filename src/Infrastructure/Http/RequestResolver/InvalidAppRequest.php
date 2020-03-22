<?php

namespace App\Infrastructure\Http\RequestResolver;

use Symfony\Component\Validator\ConstraintViolationListInterface;
use Throwable;

class InvalidAppRequest extends \Exception
{
    /**
     * @var ConstraintViolationListInterface
     */
    private $errors;

    public function __construct(
        ConstraintViolationListInterface $errors,
        string $message = '',
        int $code = 0,
        Throwable $previous = null
    )
    {
        $this->errors = $errors;
        parent::__construct($message, $code, $previous);
    }

    public function errors(): ConstraintViolationListInterface
    {
        return $this->errors;
    }
}
