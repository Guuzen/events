<?php
declare(strict_types=1);

namespace App\Event\Model;

final class ActiveTariffType
{
    private const TYPE_SILVER   = 'silver';
    private const TYPE_GOLD     = 'gold';
    private const TYPE_PLATINUM = 'platinum';
    private const TYPE_DIAMOND  = 'diamond';

    private $type;

    public function __construct(string $type)
    {
        if ($type === self::TYPE_SILVER) {
            $this->type = $type;
            return;
        }
        if ($type === self::TYPE_GOLD) {
            $this->type = $type;
            return;
        }
        if ($type === self::TYPE_PLATINUM) {
            $this->type = $type;
            return;
        }
        if ($type === self::TYPE_DIAMOND) {
            $this->type = $type;
            return;
        }

        throw new UnknownTariffType();
    }

    public function equals(ActiveTariffType $activeTariffType): bool
    {
        return $this->type === $activeTariffType->type;
    }
}
