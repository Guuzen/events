<?php

namespace App\Order\Action;

use App\Common\AppRequest;
use Symfony\Component\Validator\Constraints as Assert;

// TODO validation
// TODO denormalizable groups or constructor requirements?
final class PlaceOrder implements AppRequest
{
    /**
     * @Assert\NotBlank
     */
    public $firstName;

    public $lastName;

    public $email;

    public $paymentMethod;

    public $tariffId;

    public $phone;

    /**
     * @var string
     */
    public $eventId;

    public function __construct(
        string $firstName,
        string $lastName,
        string $email,
        string $paymentMethod,
        string $tariffId,
        string $phone
    ) {
        $this->firstName     = $firstName;
        $this->lastName      = $lastName;
        $this->email         = $email;
        $this->paymentMethod = $paymentMethod;
        $this->tariffId      = $tariffId;
        $this->phone         = $phone;
    }
}
