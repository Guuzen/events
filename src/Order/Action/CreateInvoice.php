<?php
declare(strict_types=1);

namespace App\Order\Action;

use App\Common\AppRequest;

final class CreateInvoice implements AppRequest
{
    public $tariffId;

    public $firstName;

    public $lastName;

    public $email;

    public $phone;

    public $company;

    public $position;

    public $promocode;

    public $lang;

    public $country;

    public $city;

    public $paymentMethod;

    public $agreeTerms;

    public $credentials;

    public function __construct(
        string $tariffId,
        string $firstName,
        string $lastName,
        string $email,
        string $phone,
        string $company,
        string $position,
        string $promocode,
        string $lang,
        string $country,
        string $city,
        string $paymentMethod,
        string $agreeTerms,
        string $credentials
    ) {
        $this->tariffId = $tariffId;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->phone = $phone;
        $this->company = $company;
        $this->position = $position;
        $this->promocode = $promocode;
        $this->lang = $lang;
        $this->country = $country;
        $this->city = $city;
        $this->paymentMethod = $paymentMethod;
        $this->agreeTerms = $agreeTerms;
        $this->credentials = $credentials;
    }
}
