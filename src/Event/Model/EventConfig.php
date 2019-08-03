<?php

namespace App\Event\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
final class EventConfig
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="string")
     */
    public $id;

    /**
     * @ORM\Column(type="string")
     */
    public $name;

    /**
     * @ORM\Column(type="string")
     */
    public $domain;
}
