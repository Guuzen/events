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
    private $start;

    /**
     * @var DateTimeImmutable
     */
    private $end;
}
