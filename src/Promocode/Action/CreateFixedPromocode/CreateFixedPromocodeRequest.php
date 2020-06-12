<?php

namespace App\Promocode\Action\CreateFixedPromocode;

use App\Event\Model\EventId;
use App\Infrastructure\Http\RequestResolver\AppRequest;
use App\Promocode\Model\AllowedTariffs\SpecificAllowedTariffs;
use App\Promocode\Model\Discount\FixedDiscount;
use App\Tariff\Model\TariffId;
use Money\Currency;
use Money\Money;

/**
 * @psalm-immutable
 */
final class CreateFixedPromocodeRequest implements AppRequest
{
    private $eventId;

    private $code;

    private $discount;

    private $useLimit;

    private $expireAt;

    private $usable;

    private $allowedTariffIds;

    /**
     * @psalm-param array{
     *      amount: string,
     *      currency: string
     * } $discount
     *
     * @param string[] $allowedTariffIds
     */
    public function __construct(
        string $eventId,
        string $code,
        array $discount,
        int $useLimit,
        string $expireAt,
        bool $usable,
        array $allowedTariffIds = []
    )
    {
        $this->discount         = $discount;
        $this->code             = $code;
        $this->useLimit         = $useLimit;
        $this->expireAt         = $expireAt;
        $this->usable           = $usable;
        $this->allowedTariffIds = $allowedTariffIds;
        $this->eventId          = $eventId;
    }

    public function toCreateFixedPromocode(): CreateFixedPromocode
    {
        $allowedTariffIds = [];
        foreach ($this->allowedTariffIds as $allowedTariffId) {
            $allowedTariffIds[] = new TariffId($allowedTariffId);
        }

        return new CreateFixedPromocode(
            new EventId($this->eventId),
            $this->code,
            new FixedDiscount(
                new Money(
                    $this->discount['amount'],
                    new Currency($this->discount['currency'])
                )
            ),
            $this->useLimit,
            new \DateTimeImmutable($this->expireAt),
            $this->usable,
            new SpecificAllowedTariffs($allowedTariffIds)
        );
    }
}
