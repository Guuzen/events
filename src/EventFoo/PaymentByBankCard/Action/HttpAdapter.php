<?php
declare(strict_types=1);

namespace App\EventFoo\PaymentByBankCard\Action;

use Symfony\Component\HttpFoundation\Response;

final class HttpAdapter
{

    public function __construct()
    {
    }

    public function pay(Pay $pay): Response
    {

    }
}
