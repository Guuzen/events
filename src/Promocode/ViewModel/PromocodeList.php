<?php

declare(strict_types=1);

namespace App\Promocode\ViewModel;

use App\Infrastructure\InlineNormalizer\InlineNormalizable;

/**
 * @InlineNormalizable()
 *
 * @psalm-immutable
 */
final class PromocodeList
{
    /**
     * @var Promocode[]
     */
    private $promocodes;
}
