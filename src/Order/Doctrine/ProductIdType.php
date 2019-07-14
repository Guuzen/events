<?php

namespace App\Order\Doctrine;

use App\Order\Model\ProductId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

final class ProductIdType extends StringType
{
    private const TYPE = 'app_product_id';

    public function convertToPHPValue($value, AbstractPlatform $platform): ProductId
    {
        // TODO create via reflection ?
        return ProductId::fromString($value);
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
