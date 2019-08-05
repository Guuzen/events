<?php

namespace App\Order\Action;

use App\Common\AppRequest;
use Symfony\Component\Validator\Constraints as Assert;

// TODO validation
// TODO denormalizable groups or constructor requirements?
final class PlaceOrder implements AppRequest
{
    /**
     * @var string
     * @Assert\NotBlank
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

    /**
     * @var string
     */
    public $phone;

    /**
     * @var string
     */
    public $eventId;

    public function __construct(
        ?string $firstName = null,
        ?string $lastName = null,
        ?string $email = null,
        ?string $paymentMethod = null,
        ?string $tariffId = null,
        ?string $phone = null
    ) {
        $this->firstName     = $firstName;
        $this->lastName      = $lastName;
        $this->email         = $email;
        $this->paymentMethod = $paymentMethod;
        $this->tariffId      = $tariffId;
        $this->phone         = $phone;
    }
}
