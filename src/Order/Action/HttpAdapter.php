<?php
declare(strict_types=1);

namespace App\Order\Action;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class HttpAdapter
{
    private $payHandler;

    public function __construct(CreateInvoiceHandler $payHandler)
    {
        $this->payHandler = $payHandler;
    }

    public function create(CreateInvoice $createInvoice, Request $request): Response
    {
        $this->payHandler->handle($createInvoice);
    }
}
