<?php

namespace App\Order\Action;

use App\Infrastructure\Http\RequestResolver\AppRequest;
use App\User\Action\CreateUser;
use Symfony\Component\Validator\Constraints as Assert;

// TODO validation

/**
 * @psalm-immutable
 */
final class PlaceOrderRequest implements AppRequest
{
    /**
     * @Assert\NotBlank
     */
    private $firstName;

    private $lastName;

    private $email;

    private $tariffId;

    private $phone;

    private $eventId;

    public function __construct(
        string $firstName,
        string $lastName,
        string $email,
        string $tariffId,
        string $phone,
        string $eventId
    )
    {
        $this->firstName = $firstName;
        $this->lastName  = $lastName;
        $this->email     = $email;
        $this->tariffId  = $tariffId;
        $this->phone     = $phone;
        $this->eventId   = $eventId;
    }

    public function toCreateUser(string $userId): CreateUser
    {
        return new CreateUser($userId, $this->firstName, $this->lastName, $this->email, $this->phone);
    }

    public function toPlaceOrder(string $userId): PlaceOrder
    {
        return new PlaceOrder($this->tariffId, $this->eventId, $userId);
    }
}
