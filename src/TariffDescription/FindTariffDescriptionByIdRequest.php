<?php

declare(strict_types=1);

namespace App\TariffDescription;

use App\Infrastructure\Http\RequestResolver\AppRequest;

// TODO remove constructors from request ?
final class FindTariffDescriptionByIdRequest implements AppRequest
{
    /** @var string */
    public $tariffId;
}
