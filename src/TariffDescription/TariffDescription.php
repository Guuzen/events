<?php

namespace App\TariffDescription;

use App\Infrastructure\Http\RequestResolver\AppRequest;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class TariffDescription implements AppRequest
{
    /**
     * @var string
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="guid")
     */
    public $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    public $tariffType;
}
