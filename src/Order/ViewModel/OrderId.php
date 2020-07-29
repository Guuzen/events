<?php

declare(strict_types=1);

namespace App\Order\ViewModel;

use App\Infrastructure\InlineNormalizer\InlineDenormalizable;
use App\Infrastructure\InlineNormalizer\InlineNormalizable;

/**
 * @InlineNormalizable()
 * @InlineDenormalizable()
 *
 * @psalm-immutable
 */
final class OrderId
{
    /**
     * @var string
     */
    private $id;

    public function equals(self $uuid): bool
    {
        return $this->id === $uuid->id;
    }

    public function __toString(): string
    {
        return $this->id;
    }
}
