<?php

namespace App\Cloudpayments;

final class PaymentStatus
{
    private const STATUS_PENDING = 'pending';

    private const STATUS_COMPLETED = 'completed';

    private const STATUS_AUTHORIZED = 'authorized';

    private $status;

    public function __construct(string $status)
    {
        $this->status = $status;
    }

    public static function pending(): self
    {
        return new self(self::STATUS_PENDING);
    }

    public static function completed(): self
    {
        return new self(self::STATUS_COMPLETED);
    }

    public static function authorized(): self
    {
        return new self(self::STATUS_AUTHORIZED);
    }
}
