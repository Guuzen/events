<?php

namespace App\EventDomain\Model;

use App\Infrastructure\Http\RequestResolver\AppRequest;
use Doctrine\ORM\Mapping as ORM;

/**
 * @psalm-immutable
 *
 * @ORM\Entity
 */
class EventDomain implements AppRequest
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="guid")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @ORM\Column(type="string")
     */
    private $domain;

    public function __construct(string $id, string $name, string $domain)
    {
        $this->id     = $id;
        $this->name   = $name;
        $this->domain = $domain;
    }
}
