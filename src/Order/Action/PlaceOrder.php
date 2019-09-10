<?php

namespace App\Order\Action;

use App\Infrastructure\Http\AppRequest;
use Symfony\Component\Validator\Constraints as Assert;

// TODO validation
/**
 * @psalm-immutable
 */
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
