<?php

declare(strict_types=1);

namespace App\User\Model;

use App\Event\Model\EventId;
use App\Order\Model\Order;
use App\Order\Model\OrderId;
use App\Order\Model\ProductId;
use App\Promocode\Model\PromocodeId;
use App\Tariff\Model\TicketTariffId;
use DateTimeImmutable;
use Money\Money;
use Doctrine\ORM\Mapping as ORM;

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
        ?PromocodeId $promocodeId,
        Money $sum,
        DateTimeImmutable $makedAt
    ): Order {
        return new Order($orderId, $eventId, $productId, $tariffId, $promocodeId, $this->id, $sum, $makedAt);
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
