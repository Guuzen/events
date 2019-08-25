<?php

namespace App\Tariff\Doctrine;

use App\Tariff\Model\TariffId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

final class TariffIdType extends StringType
{
    public function convertToPHPValue($value, AbstractPlatform $platform): TariffId
    {
        // TODO create via reflection ?
        return TariffId::fromString((string) $value);
    }

    public function getName(): string
    {
        return 'app_tariff_id';
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
