<?php
declare(strict_types=1);

namespace App\Tariff\Model;

final class ProductType
{
    private const TYPE_TICKET = 'ticket';

    private const TYPE_BROADCAST_LINK = 'broadcast_link';

    private $type;

    public function __construct(string $type)
    {
        $this->type = $type;
    }
}
