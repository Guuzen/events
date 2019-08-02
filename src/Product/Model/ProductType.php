<?php

namespace App\Product\Model;

use App\Product\Model\Exception\UnknownProductType;

final class ProductType
{
    private const TYPE_SILVER_PASS = 'silver_pass';

    private const TYPE_GOLD_PASS = 'gold_pass';

    private const TYPE_PLATINUM_PASS = 'platinum_pass';

    private const TYPE_BROADCAST_LINK = 'broadcast_link';

    private $type;

    public function __construct(string $type)
    {
        if (self::TYPE_SILVER_PASS === $type) {
            $this->type = $type;

            return;
        }
        if (self::TYPE_GOLD_PASS === $type) {
            $this->type = $type;

            return;
        }
        if (self::TYPE_PLATINUM_PASS === $type) {
            $this->type = $type;

            return;
        }
        if (self::TYPE_BROADCAST_LINK === $type) {
            $this->type = $type;

            return;
        }

        throw new UnknownProductType();
    }

    public static function silverPass(): self
    {
        return new self(self::TYPE_SILVER_PASS);
    }

    public static function goldPass(): self
    {
        return new self(self::TYPE_GOLD_PASS);
    }

    public static function platinumPass(): self
    {
        return new self(self::TYPE_PLATINUM_PASS);
    }

    public static function broadcastLink(): self
    {
        return new self(self::TYPE_BROADCAST_LINK);
    }

    public function __toString(): string
    {
        return $this->type;
    }
}
