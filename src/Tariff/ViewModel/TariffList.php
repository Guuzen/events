<?php

declare(strict_types=1);

namespace App\Tariff\ViewModel;

use App\Infrastructure\InlineNormalizer\InlineDenormalizable;

/**
 * @InlineDenormalizable()
 *
 * @psalm-immutable
 */
final class TariffList
{
    /**
     * @var Tariff[]
     */
    private $tariffs;
}
