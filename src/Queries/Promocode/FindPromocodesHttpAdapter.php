<?php

namespace App\Queries\Promocode;

use App\Infrastructure\Http\AppController;
use App\Queries\Promocode\FindPromocodesInList\FindPromocodesInList;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class FindPromocodesHttpAdapter extends AppController
{
    private $promocodeQueries;

    public function __construct(PromocodeQueries $promocodeQueries)
    {
        $this->promocodeQueries = $promocodeQueries;
    }

    /**
     * @Route("/admin/promocode/list", methods={"GET"})
     */
    public function findInList(FindPromocodesInList $findPromocodesInList): Response
    {
        $promocodes = $this->promocodeQueries->findInList($findPromocodesInList->eventId);

        return $this->response($promocodes);
    }
}
