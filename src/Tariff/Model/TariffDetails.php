<?php

namespace App\Tariff\Model;

use Doctrine\ORM\Mapping as ORM;
use App\Infrastructure\Persistence\UuidType;

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
     * @ORM\Column(type=TariffDetailsId::class, options={"typeClass": UuidType::class})
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
