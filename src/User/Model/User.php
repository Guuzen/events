<?php

namespace App\User\Model;

use App\Infrastructure\DomainEvent\Entity;
use App\Order\Model\OrderId;
use App\Product\Model\ProductId;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="`user`")
 */
class User extends Entity
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="app_event_id")
     */
    private $id;

    /**
     * @ORM\Column(type="app_user_contacts")
     */
    private $fullName;

    /**
     * @ORM\Column(type="app_user_fullname")
     */
    private $contacts;

    /**
     * @ORM\Column(type="app_order_id")
     */
    private $orderId;

    public function __construct(UserId $id, OrderId $orderId, FullName $fullName, Contacts $contacts)
    {
        $this->id       = $id;
        $this->fullName = $fullName;
        $this->contacts = $contacts;
        $this->orderId  = $orderId;
    }
}
