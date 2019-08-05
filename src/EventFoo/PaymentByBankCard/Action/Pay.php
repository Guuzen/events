<?php


namespace App\EventFoo\PaymentByBankCard\Action;

use App\Common\AppRequest;

final class Pay implements AppRequest
{
    public $invoiceId;

    public $lang;

    public $country;

    public $city;

    public $ip;

    public $pan;

    public $expYear;

    public $expMonth;

    public $cvc;

    public $holder;

    public function __construct(
        string $invoiceId,
        string $lang,
        string $country,
        string $city,
        string $ip,
        string $pan,
        string $expYear,
        string $expMonth,
        string $cvc,
        string $holder
    ) {
        $this->invoiceId = $invoiceId;
        $this->lang = $lang;
        $this->country = $country;
        $this->city = $city;
        $this->ip = $ip;
        $this->pan = $pan;
        $this->expYear = $expYear;
        $this->expMonth = $expMonth;
        $this->cvc = $cvc;
        $this->holder = $holder;
    }
}
