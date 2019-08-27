<?php

namespace App\User\Action\Register;

use App\Infrastructure\Http\AppRequest;
use Symfony\Component\Validator\Constraints as Assert;

class RegisterRequest implements AppRequest
{
    /**
     * @Assert\NotBlank
     */
    public $firstName;

    /**
     * @Assert\NotBlank
     */
    public $lastName;

    /**
     * @Assert\NotBlank
     * @Assert\Email
     */
    public $email;

    /**
     * @Assert\NotBlank
     */
    public $phone;

    /**
     * @Assert\NotBlank
     */
    public $country;

    /**
     * @Assert\NotBlank
     */
    public $city;
}
