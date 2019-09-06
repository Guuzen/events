<?php

namespace App\Promocode\Doctrine;

use App\Promocode\Model\PromocodeId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

final class PromocodeIdType extends StringType
{
    public function convertToPHPValue($value, AbstractPlatform $platform): ?PromocodeId
    {
        // TODO create via reflection ?
        if (null === $value) {
            return null;
        }

        return new PromocodeId((string) $value);
    }

    public function getName(): string
    {
        return 'app_promocode_id';
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
