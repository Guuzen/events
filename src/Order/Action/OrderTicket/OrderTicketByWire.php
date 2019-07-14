<?php

namespace App\Order\Action\OrderTicket;

use App\Common\AppRequest;
use Symfony\Component\Validator\Constraints as Assert;

// TODO validation
final class OrderTicketByWire implements AppRequest
{
    /**
     * @var string
     * @Assert\NotBlank()
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
    public $tariffId;

    public function __construct(string $firstName, string $lastName, string $email, string $paymentMethod, string $tariffId)
    {
        $this->firstName        = $firstName;
        $this->lastName         = $lastName;
        $this->email            = $email;
        $this->paymentMethod    = $paymentMethod;
        $this->tariffId         = $tariffId;
    }
}
