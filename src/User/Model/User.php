<?php

namespace App\User\Model;

use App\Infrastructure\DomainEvent\Entity;
use App\Infrastructure\Persistence\DoctrineTypesInitializer\JsonDocumentType;
use App\Infrastructure\Persistence\UuidType;
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
     * @ORM\Column(type=UserId::class, options={"typeClass": UuidType::class})
     */
    private $id;

    /**
     * @ORM\Column(type=FullName::class, options={"typeClass": JsonDocumentType::class})
     */
    private $fullName;

    /**
     * @ORM\Column(type=Contacts::class, options={"typeClass": JsonDocumentType::class})
     */
    private $contacts;

    /**
     * @ORM\Column(type=OrderId::class, options={"typeClass": UuidType::class})
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
