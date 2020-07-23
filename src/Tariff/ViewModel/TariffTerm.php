<?php

namespace App\Tariff\ViewModel;

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
