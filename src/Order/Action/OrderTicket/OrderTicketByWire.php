<?php

namespace App\Order\Action\OrderTicket;

use App\Common\AppRequest;

// TODO validation
final class OrderTicketByWire implements AppRequest
{
    /**
     * @var string
     */
    public $firstName;

    /**
     * @var string
     */
    public $lastName;

    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $paymentMethod;

    /**
     * @var string
     */
    public $tariff;

    public function __construct(string $firstName, string $lastName, string $email, string $paymentMethod, string $tariff)
    {
        $this->firstName     = $firstName;
        $this->lastName      = $lastName;
        $this->email         = $email;
        $this->paymentMethod = $paymentMethod;
        $this->tariff        = $tariff;
    }
}
