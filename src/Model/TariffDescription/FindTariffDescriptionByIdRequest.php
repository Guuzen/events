<?php

declare(strict_types=1);

namespace App\Model\TariffDescription;

use App\Infrastructure\Http\RequestResolver\AppRequest;

// TODO remove constructors from request ?
/**
 * @psalm-immutable
 */
final class FindTariffDescriptionByIdRequest implements AppRequest
{
    /** @var string */
    public $tariffId;
}
