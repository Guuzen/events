<?php

namespace App\Tariff\Doctrine;

use App\Tariff\Model\TicketTariffId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

final class TicketTariffIdType extends StringType
{
    private const TYPE = 'app_ticket_tariff_id';

    public function convertToPHPValue($value, AbstractPlatform $platform): TicketTariffId
    {
        // TODO create via reflection ?
        return TicketTariffId::fromString($value);
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
