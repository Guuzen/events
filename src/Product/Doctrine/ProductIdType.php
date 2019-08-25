<?php

namespace App\Product\Doctrine;

use App\Product\Model\ProductId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

final class ProductIdType extends StringType
{
    public function convertToPHPValue($value, AbstractPlatform $platform): ProductId
    {
        // TODO create via reflection ?
        return ProductId::fromString((string) $value);
    }

    public function getName(): string
    {
        return 'app_product_id';
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
