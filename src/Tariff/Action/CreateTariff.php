<?php

namespace App\Tariff\Action;

use App\Common\AppRequest;

final class CreateTariff implements AppRequest
{
    // TODO detect event id by host
    public $eventId;

    public $productType;

    // TODO embedded object?
    /**
     * @var array{
     *      segments: array{
     *          price: array{
     *              amount: string,
     *              currency: string,
     *          },
     *          term: array{
     *              start: string,
     *              end: string,
     *          },
     *      },
     * }[]
     */
    public $priceNet;

    /**
     * @param array{
     *      segments: array{
     *          price: array{
     *              amount: string,
     *              currency: string,
     *          },
     *          term: array{
     *              start: string,
     *              end: string,
     *          },
     *      },
     * }[] $priceNet
     */
    public function __construct(string $eventId, string $productType, array $priceNet)
    {
        $this->eventId     = $eventId;
        $this->productType = $productType;
        $this->priceNet    = $priceNet;
    }
}
