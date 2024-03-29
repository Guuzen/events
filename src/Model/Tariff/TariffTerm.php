<?php

namespace App\Model\Tariff;

use App\Model\Tariff\Exception\TariffTermMustStartBeforeEnd;
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

    public function __construct(DateTimeImmutable $start, DateTimeImmutable $end)
    {
        if ($start > $end) {
            throw new TariffTermMustStartBeforeEnd('');
        }
        $this->start = $start;
        $this->end   = $end;
    }

    public function includes(DateTimeImmutable $dateTime): bool
    {
        return $dateTime >= $this->start && $dateTime < $this->end;
    }

    public function intersects(TariffTerm $term): bool
    {
        return $this->includes($term->start) || $this->includes($term->end);
    }
}
