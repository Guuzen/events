<?php

namespace App\User\Doctrine;

use App\User\Model\UserId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

final class UserIdType extends StringType
{
    private const TYPE = 'app_user_id';

    public function convertToPHPValue($value, AbstractPlatform $platform): UserId
    {
        // TODO create via reflection ?
        return UserId::fromString($value);
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
