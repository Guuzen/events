<?php

namespace App\Queries\Promocode;

use App\Infrastructure\Http\AppController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/promocode", methods={"GET"})
 */
final class FindPromocodesHttpAdapter extends AppController
{
    private $promocodeQueries;

    public function __construct(PromocodeQueries $promocodeQueries)
    {
        $this->promocodeQueries = $promocodeQueries;
    }

    /**
     * @Route("/list")
     */
    public function findAll(): Response
    {
        $promocodes = $this->promocodeQueries->findAll();

        return $this->response($promocodes);
    }
}
