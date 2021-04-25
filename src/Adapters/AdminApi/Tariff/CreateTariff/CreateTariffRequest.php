<?php

namespace App\Adapters\AdminApi\Tariff\CreateTariff;

use App\Model\Event\EventId;
use App\Infrastructure\Http\RequestResolver\AppRequest;
use App\Model\Tariff\ProductType;
use App\Model\Tariff\TariffPriceNet;
use App\Model\Tariff\TariffSegment;
use App\Model\Tariff\TariffTerm;
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
