<?php

namespace App\Tariff\Query;

use DateTimeImmutable;

/**
 * @psalm-immutable
 */
final class TariffTerm
{
    /**
     * @var DateTimeImmutable
     */
    public $start;

    /**
     * @var DateTimeImmutable
     */
    public $end;
}
