<?php

namespace App\Order\Model;

final class PaymentMethod
{
    private const METHOD_CLOUDPAYMENTS = 'cloudpayments';

    private const METOD_BY_ACCOUNT = 'by-account';

    private $method;

    public function __construct(string $method)
    {
        if (self::METHOD_CLOUDPAYMENTS === $method) {
            $this->method = $method;

            return;
        }
        if (self::METOD_BY_ACCOUNT === $method) {
            $this->method = $method;

            return;
        }

        throw new UnknownPaymentMethod();
    }

    public function equals(PaymentMethod $paymentMethod): bool
    {
        return $this->method === $paymentMethod->method;
    }

    public static function cloudpayments(): self
    {
        return new self(self::METHOD_CLOUDPAYMENTS);
    }

    public static function byAccount(): self
    {
        return new self(self::METOD_BY_ACCOUNT);
    }
}
