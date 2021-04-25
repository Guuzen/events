<?php

namespace App\Adapters\ClientApi\Order\PlaceOrder;

use App\Infrastructure\Http\RequestResolver\AppRequest;
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
    public $firstName;

    public $lastName;

    public $email;

    public $tariffId;

    public $phone;

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
}
