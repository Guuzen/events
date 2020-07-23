<?php

namespace App\TariffDescription;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class TariffDescription
{
    /**
     * @var TariffDescriptionId
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type=TariffDescriptionId::class)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $tariffType;

    public function __construct(TariffDescriptionId $id, string $tariffType)
    {
        $this->id         = $id;
        $this->tariffType = $tariffType;
    }
}
