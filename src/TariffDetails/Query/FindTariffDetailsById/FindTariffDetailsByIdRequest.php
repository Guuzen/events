<?php

declare(strict_types=1);

namespace App\TariffDetails\Query\FindTariffDetailsById;

use App\Infrastructure\Http\RequestResolver\AppRequest;

// TODO remove constructors from request ?
final class FindTariffDetailsByIdRequest implements AppRequest
{
    /** @var string */
    public $tariffId;
}
