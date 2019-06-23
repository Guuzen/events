<?php
declare(strict_types=1);

namespace App\Common;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\JsonType;
use Money\Money;

class MoneyType extends JsonType
{
    public const MONEY = 'money';

    public function convertToPHPValue($value, AbstractPlatform $platform): ?Money
    {
        $val = parent::convertToPHPValue($value, $platform);

        return new Money($val['amount'], $val['currency']);
    }

    public function getName(): string
    {
        return self::MONEY;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform)
    {
        return true;
    }
}
