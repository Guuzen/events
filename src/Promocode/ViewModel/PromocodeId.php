<?php

namespace App\Promocode\ViewModel;

use App\Infrastructure\InlineNormalizer\InlineDenormalizable;
use App\Infrastructure\InlineNormalizer\InlineNormalizable;

/**
 * @todo psalm internal
 *
 * @InlineNormalizable()
 * @InlineDenormalizable()
 *
 * @psalm-immutable
 */
final class PromocodeId
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
