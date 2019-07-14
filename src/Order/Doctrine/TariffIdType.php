<?php
declare(strict_types=1);

namespace App\Order\Doctrine;

use App\Order\Model\TariffId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

final class TariffIdType extends StringType
{
    private const TYPE = 'app_tariff_id';

    public function convertToPHPValue($value, AbstractPlatform $platform): TariffId
    {
        // TODO create via reflection ?
        return TariffId::fromString($value);
    }

    public function getName(): string
    {
        return self::TYPE;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
