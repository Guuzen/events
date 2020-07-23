<?php

namespace App\Product\Model;

use App\Infrastructure\InlineNormalizer\InlineDenormalizable;
use App\Infrastructure\Persistence\DBALTypes\JsonDocumentType;
use App\Infrastructure\Persistence\DBALTypesInitializer\CustomTypeAnnotation as DBALType;
use Exception;
use function sprintf;

/**
 * TODO move to tariff ?
 * @InlineDenormalizable()
 *
 * @DBALType(typeClass=JsonDocumentType::class)
 *
 * @psalm-immutable
 */
final class ProductType
{
    public const BROADCAST_LINK = 'broadcast_link';

    public const TICKET = 'ticket';

    /**
     * @var string
     */
    private $type;

    public function __construct(string $type)
    {
        if (self::BROADCAST_LINK === $type) {
            $this->type = $type;

            return;
        }
        if (self::TICKET === $type) {
            $this->type = $type;

            return;
        }

        throw new Exception(sprintf('unknown product type %s', $type));
    }

    public static function broadcastLink(): self
    {
        return new self(self::BROADCAST_LINK);
    }

    public static function ticket(): self
    {
        return new self(self::TICKET);
    }

    public function equals(self $productType): bool
    {
        return $this->type === $productType->type;
    }

    public function __toString(): string
    {
        return $this->type;
    }
}
