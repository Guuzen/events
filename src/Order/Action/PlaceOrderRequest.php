<?php

namespace App\Order\Action;

use App\Infrastructure\Http\RequestResolver\AppRequest;
use App\Order\Model\OrderId;
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

    public function __construct(
        string $firstName,
        string $lastName,
        string $email,
        string $tariffId,
        string $phone
    )
    {
        $this->firstName = $firstName;
        $this->lastName  = $lastName;
        $this->email     = $email;
        $this->tariffId  = $tariffId;
        $this->phone     = $phone;
    }

    public function toCreateUser(string $userId, OrderId $orderId): CreateUser
    {
        return new CreateUser($userId, $orderId, $this->firstName, $this->lastName, $this->email, $this->phone);
    }

    public function toPlaceOrder(string $userId, string $eventId): PlaceOrder
    {
        return new PlaceOrder($this->tariffId, $eventId, $userId);
    }
}
