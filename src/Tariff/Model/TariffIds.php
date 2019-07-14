<?php
declare(strict_types=1);

namespace App\Tariff\Model;

use App\Order\Model\TariffId;
use Traversable;

final class TariffIds implements \IteratorAggregate
{
    /**
     * @var TariffId[]
     */
    private $tariffIds;

    public function __construct(array $tariffIds)
    {
        $this->tariffIds = $tariffIds;
    }

    public function push(TariffId $tariffId): void
    {
        $this->tariffIds[] = $tariffId;
    }

    public function contains(TariffId $tariffId): bool
    {
        foreach ($this->tariffIds as $id) {
            if ($id->equals($tariffId)) {
                return true;
            }
        }

        return false;
    }

    public function containsAllOf(TariffIds $tariffIds): bool
    {
        foreach ($tariffIds as $tariffId) {
            if ($this->contains($tariffId)) {
                return false;
            }
        }

        return true;
    }

    public function getIterator(): Traversable
    {
        return yield from $this->tariffIds;
    }
}
