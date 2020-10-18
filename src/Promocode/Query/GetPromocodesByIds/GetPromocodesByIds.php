<?php

declare(strict_types=1);

namespace App\Promocode\Query\GetPromocodesByIds;

final class GetPromocodesByIds
{
    public $promocodesIds;

    /**
     * @param string[] $promocodesIds
     */
    public function __construct(array $promocodesIds)
    {
        $this->promocodesIds = $promocodesIds;
    }
}
