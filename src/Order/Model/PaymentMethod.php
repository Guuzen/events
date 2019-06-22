<?php
declare(strict_types=1);

namespace App\Order\Model;

final class PaymentMethod
{
    private const METHOD_CLOUDPAYMENTS = 'cloudpayments';

    private const METOD_BY_ACCOUNT = 'by-account';

    private $method;

    public function __construct(string $method)
    {
        if ($method === self::METHOD_CLOUDPAYMENTS) {
            $this->method = $method;
            return;
        }
        if ($method === self::METOD_BY_ACCOUNT) {
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
