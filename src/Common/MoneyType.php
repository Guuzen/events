<?php

namespace App\Common;

use App\Infrastructure\Persistence\DoctrineTypesInitializer\JsonDocumentType;
use Money\Money;

final class MoneyType extends JsonDocumentType
{
    protected function className(): string
    {
        return Money::class;
    }

    public function getName(): string
    {
        return 'app_money';
    }
}
