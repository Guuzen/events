<?php

namespace App\Tariff\AdminApi\CreateTariff;

use App\Event\Model\EventId;
use App\Infrastructure\Http\RequestResolver\AppRequest;
use App\Tariff\Model\ProductType;
use App\Tariff\Model\TariffPriceNet;
use App\Tariff\Model\TariffSegment;
use App\Tariff\Model\TariffTerm;
use Money\Currency;
use Money\Money;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @psalm-immutable
 */
final class CreateTariffRequest implements AppRequest
{
    public $eventId;

    public $segments;

    /**
     * @Assert\NotBlank()
     */
    public $productType;

    /**
     * @psalm-param array<
     *      array-key,
     *      array{
     *          price: array{amount: string, currency: string},
     *          term: array{start: string, end: string},
     *      }
     * > $segments
     */
    public function __construct(string $eventId, array $segments, string $productType)
    {
        $this->eventId     = $eventId;
        $this->segments    = $segments;
        $this->productType = $productType;
    }
}
