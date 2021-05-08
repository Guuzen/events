<?php

namespace App\Model\User;

use App\Infrastructure\DomainEvent\Entity;
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
     * @ORM\Column(type=UserId::class)
     */
    private $id;

    /**
     * @ORM\Column(type=FullName::class)
     */
    private $fullName;

    /**
     * @ORM\Column(type=Contacts::class)
     */
    private $contacts;

    public function __construct(UserId $id, FullName $fullName, Contacts $contacts)
    {
        $this->id       = $id;
        $this->fullName = $fullName;
        $this->contacts = $contacts;
    }
}
