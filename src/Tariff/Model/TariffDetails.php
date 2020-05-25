<?php

namespace App\Tariff\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 *
 * TODO remove this ?
 */
class TariffDetails
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="app_tariff_details_id")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $tariffType;

    public function __construct(TariffDetailsId $id, string $tariffType)
    {
        $this->id         = $id;
        $this->tariffType = $tariffType;
    }
}
