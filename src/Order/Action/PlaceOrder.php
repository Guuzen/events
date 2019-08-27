<?php

namespace App\Order\Action;

use App\Infrastructure\Http\AppRequest;
use Symfony\Component\Validator\Constraints as Assert;

// TODO validation
// TODO denormalizable groups or constructor requirements?
final class PlaceOrder implements AppRequest
{
    /**
     * @Assert\NotBlank
     * @readonly
     */
    public $firstName;

    /**
     * @readonly
     */
    public $lastName;

    /**
     * @readonly
     */
    public $email;

    /**
     * @readonly
     */
    public $paymentMethod;

    /**
     * @readonly
     */
    public $tariffId;

    /**
     * @readonly
     */
    public $phone;

    /**
     * @readonly
     */
    public $eventId;

    public function __construct(
        string $firstName,
        string $lastName,
        string $email,
        string $paymentMethod,
        string $tariffId,
        string $phone,
        string $eventId
    ) {
        $this->firstName     = $firstName;
        $this->lastName      = $lastName;
        $this->email         = $email;
        $this->paymentMethod = $paymentMethod;
        $this->tariffId      = $tariffId;
        $this->phone         = $phone;
        $this->eventId       = $eventId;
    }
}
