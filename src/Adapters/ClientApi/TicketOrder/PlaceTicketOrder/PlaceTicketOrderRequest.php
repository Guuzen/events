<?php

declare(strict_types=1);

namespace App\Adapters\ClientApi\TicketOrder\PlaceTicketOrder;

use App\Infrastructure\Http\RequestResolver\AppRequest;

/**
 * @psalm-immutable
 */
final class PlaceTicketOrderRequest implements AppRequest
{
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