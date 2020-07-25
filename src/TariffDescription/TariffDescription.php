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
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $tariffType;

    public function __construct(string $id, string $tariffType)
    {
        $this->id         = $id;
        $this->tariffType = $tariffType;
    }
}
