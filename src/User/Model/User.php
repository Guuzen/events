<?php
declare(strict_types=1);

namespace App\User\Model;

use App\Event\Model\Event;
use App\Event\Model\EventId;
use App\EventFoo\Invoice\Action\CreateInvoice;
use App\Order\Model\Order;
use App\Order\Model\OrderId;
use App\Order\Model\ProductId;
use App\Product\Model\Product;
use App\Promocode\Model\Promocode;
use App\Promocode\Model\PromocodeId;
use App\Tariff\Model\Tariff;
use App\Tariff\Model\TariffId;
use DateTimeImmutable;
use Money\Money;

class User
{
    private $id;

    private $fullName;

    private $contacts;

    private $geo;

    public function __construct(UserId $id, FullName $fullName, Contacts $contacts, Geo $geo)
    {
        $this->id       = $id;
        $this->fullName = $fullName;
        $this->contacts = $contacts;
        $this->geo      = $geo;
    }

    public function makeOrder(
        OrderId $orderId,
        EventId $eventId,
        TariffId $tariffId,
        ?PromocodeId $promocodeId,
        ProductId $productId,
        Money $sum,
        DateTimeImmutable $makedAt
    ): Order
    {
        return new Order($orderId, $eventId, $promocodeId, $tariffId, $productId, $this->id, $sum, $makedAt);
    }

//    public static function registered(RegisterRequest $request): self
//    {
//        $fullName = new FullName($request->firstName, $request->lastName);
//        $contacts = new Contacts($request->email, $request->phone);
//        $geo = new Geo($request->city, $request->country);
//        $user = new self(Uuid::uuid4(), $fullName, $contacts, $geo);
//
//        return $user;
//    }
}
