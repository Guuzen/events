<?php

namespace App\Common;

use DateTimeImmutable;

final class DateTimeType extends JsonDocumentType
{
    protected function className(): string
    {
        return DateTimeImmutable::class;
    }

    public function getName(): string
    {
        return 'app_datetime';
    }
}
