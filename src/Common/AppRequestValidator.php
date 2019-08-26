<?php

namespace App\Common;

use Symfony\Component\Validator\Validator\ValidatorInterface;

final class AppRequestValidator
{
    private $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function validate(AppRequest $appRequest): \Generator
    {
        $errors = $this->validator->validate($appRequest);
        if (count($errors) > 0) {
            throw new InvalidAppRequest($errors);
        }

        yield $appRequest;
    }
}
