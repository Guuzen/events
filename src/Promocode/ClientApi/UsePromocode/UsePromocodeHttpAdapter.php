<?php

declare(strict_types=1);

namespace App\Promocode\ClientApi\UsePromocode;

use App\Event\Model\EventId;
use App\Infrastructure\Http\AppController\AppController;
use App\Order\Model\OrderId;
use App\Promocode\Model\Promocodes;
use App\Tariff\Model\TariffId;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class UsePromocodeHttpAdapter extends AppController
{
    private $promocodes;

    public function __construct(Promocodes $promocodes)
    {
        $this->promocodes = $promocodes;
    }

    /**
     * @Route("/promocode/use", methods={"POST"})
     */
    public function __invoke(UsePromocodeRequest $request, EventId $eventId): Response
    {
        $promocode = $this->promocodes->findByCode($request->code, $eventId);

        $promocode->use(new OrderId($request->orderId), new TariffId($request->tariffId), new \DateTimeImmutable());

        $this->flush();

        return $this->response([]);
    }
}
