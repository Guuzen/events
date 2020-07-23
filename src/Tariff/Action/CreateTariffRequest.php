<?php

namespace App\Tariff\Action;

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
    private $eventId;

    private $tariffType;

//    /**
//     * @psalm-var array<
//     *      array-key,
//     *      array{
//     *          price: array{amount: string, currency: string},
//     *          term: array{start: string, end: string},
//     *      }
//     * >
//     */
    private $segments;

    /**
     * @Assert\NotBlank()
     */
    private $productType;

    /**
     * @psalm-param array<
     *      array-key,
     *      array{
     *          price: array{amount: string, currency: string},
     *          term: array{start: string, end: string},
     *      }
     * > $segments
     */
    public function __construct(string $eventId, string $tariffType, array $segments, string $productType)
    {
        $this->eventId     = $eventId;
        $this->tariffType  = $tariffType;
        $this->segments    = $segments;
        $this->productType = $productType;
    }

    public function toCreateTariff(): CreateTariff
    {
        $tariffSegments = [];
        foreach ($this->segments as $segment) {
            $tariffSegments[] = new TariffSegment(
                new Money(
                    $segment['price']['amount'],
                    new Currency($segment['price']['currency'])
                ),
                new TariffTerm(
                    new \DateTimeImmutable($segment['term']['start']),
                    new \DateTimeImmutable($segment['term']['end'])
                )
            );
        }

        return new CreateTariff(
            new EventId($this->eventId),
            $this->tariffType,
            new TariffPriceNet($tariffSegments),
            new ProductType($this->productType)
        );
    }
}
