<?php

declare(strict_types=1);

namespace App\Order\AdminApi;

use App\Infrastructure\ResComposer\Link\OneToOne;
use App\Infrastructure\ResComposer\Promise;
use App\Infrastructure\ResComposer\Resource;
use App\Infrastructure\ResComposer\ResourceResolver;
use App\Promocode\AdminApi\PromocodeLoader;
use App\Promocode\AdminApi\Resource\PromocodeResource;
use App\Promocode\Model\Discount\Discount;
use Money\Money;

/**
 * @psalm-immutable
 */
final class OrderResource implements Resource
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $eventId;

    /**
     * @var string
     */
    public $tariffId;

    /**
     * @var string
     */
    public $productType;

    /**
     * @var string
     */
    public $userId;

    /**
     * TODO new type money for resources ?
     *
     * @var Money
     */
    public $price;

    /**
     * @var Money
     */
    public $total;

    /**
     * @var \DateTimeImmutable
     */
    public $makedAt;

    /**
     * @var bool
     */
    public $paid;

    /**
     * @var bool
     */
    public $cancelled;

    /**
     * @var PromocodeResource|null
     */
    public $promocodeId;

    public function getId(): string
    {
        return $this->id;
    }

    public function getEventId(): string
    {
        return $this->eventId;
    }

    public function getTariffId(): string
    {
        return $this->tariffId;
    }

    public function getProductType(): string
    {
        return $this->productType;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getPrice(): Money
    {
        return $this->price;
    }

    public function getTotal(): Money
    {
        if ($this->promocodeId === null) {
            return $this->price;
        }

        return $this->promocodeId->discount->apply($this->price);
    }

    public function getMakedAt(): \DateTimeImmutable
    {
        return $this->makedAt;
    }

    public function isPaid(): bool
    {
        return $this->paid;
    }

    public function isCancelled(): bool
    {
        return $this->cancelled;
    }

    public function getPromocodeId(): ?string
    {
        if ($this->promocodeId === null) {
            return null;
        }

        return $this->promocodeId->id;
    }

    public function getDiscount(): ?Discount
    {
        if ($this->promocodeId === null) {
            return null;
        }

        return $this->promocodeId->discount;
    }


    /** @psalm-suppress InvalidArgument */
    public static function create(): ResourceResolver
    {
        return new ResourceResolver(
            self::class,
            new OneToOne('id'),
            PromocodeResource::class,
            PromocodeLoader::class,
            fn(self $order) => [
                new Promise(
                    fn(self $order) => $order->promocodeId,
                    /** @psalm-suppress InaccessibleProperty */
                    fn(self $order, ?PromocodeResource $promocode) => $order->promocodeId = $promocode,
                    $order,
                )
            ],
        );
    }
}
