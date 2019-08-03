<?php

namespace App\User\Model;

use App\Event\Model\EventId;
use App\Order\Model\Order;
use App\Order\Model\OrderId;
use App\Product\Model\ProductId;
use App\Tariff\Model\TicketTariffId;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Money\Money;

/**
 * @ORM\Entity
 * @ORM\Table(name="`user`")
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="app_event_id")
     */
    private $id;

    /**
     * @ORM\Column(type="json_document")
     */
    private $fullName;

    /**
     * @ORM\Column(type="json_document")
     */
    private $contacts;

    public function __construct(UserId $id, FullName $fullName, Contacts $contacts)
    {
        $this->id       = $id;
        $this->fullName = $fullName;
        $this->contacts = $contacts;
    }

    public function makeOrder(
        OrderId $orderId,
        EventId $eventId,
        ProductId $productId,
        TicketTariffId $tariffId,
        Money $sum,
        DateTimeImmutable $makedAt
    ): Order {
        return new Order($orderId, $eventId, $productId, $tariffId, $this->id, $sum, $makedAt);
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
