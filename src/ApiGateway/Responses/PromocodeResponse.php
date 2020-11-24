<?php

declare(strict_types=1);

namespace App\ApiGateway\Responses;

use App\Infrastructure\ResponseComposer\Schema;
use App\Infrastructure\ResponseComposer\SchemaProvider;
use App\Promocode\Model\Discount\Discount;
use App\Promocode\Query\PromocodeResource;

final class PromocodeResponse implements SchemaProvider
{
    private PromocodeResource $promocode;

    public function __construct(PromocodeResource $promocode)
    {
        $this->promocode = $promocode;
    }

    public static function schema(): Schema
    {
        return new Schema(self::class);
    }

    public function getId(): string
    {
        return $this->promocode->id;
    }

    public function getEventId(): string
    {
        return $this->promocode->eventId;
    }

    public function getCode(): string
    {
        return $this->promocode->code;
    }

    public function getDiscount(): Discount
    {
        return $this->promocode->discount;
    }

    public function getUseLimit(): int
    {
        return $this->promocode->useLimit;
    }

    public function getExpireAt(): \DateTimeImmutable
    {
        return $this->promocode->expireAt;
    }

    public function getAllowedTariffs(): array
    {
        return $this->promocode->allowedTariffs;
    }

    public function getUsedInOrders(): array
    {
        return $this->promocode->usedInOrders;
    }

    public function getUsable(): bool
    {
        return $this->promocode->usable;
    }
}
