<?php

declare(strict_types=1);

namespace App\Promocode\Query\GetPromocodeById;

use App\Promocode\Model\PromocodeId;

/**
 * @psalm-immutable
 */
final class GetPromocodeById
{
    public $promocodeId;

    public function __construct(PromocodeId $promocodeId)
    {
        $this->promocodeId = $promocodeId;
    }
}
