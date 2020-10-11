<?php

declare(strict_types=1);

namespace App\Promocode\Query\ApplyDiscountHandler;

use App\Promocode\Model\Discount\Discount;
use Doctrine\DBAL\Connection;
use Money\Money;

final class ApplyDiscountHandler
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function execute(ApplyDiscount $query): Money
    {
        $stmt = $this->connection->prepare(
            '
            select
                discount
            from
                promocode
            where
                promocode.id = :promocode_id
            '
        );

        $stmt->bindValue('promocode_id', $query->promocodeId);
        $stmt->execute();

        /** @var string|false $discountJson */
        $discountJson = $stmt->fetchColumn();

        /** @var Discount $discount */
        $discount = $this->connection->convertToPHPValue($discountJson, Discount::class);

        return $discount->apply($query->money);
    }
}
