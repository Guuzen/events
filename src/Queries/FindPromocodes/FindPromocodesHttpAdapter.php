<?php

namespace App\Queries\FindPromocodes;

use App\Common\AppController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/promocode/list", methods={"GET"})
 */
final class FindPromocodesHttpAdapter extends AppController
{
    private $findPromocodes;

    public function __construct(FindPromocodes $findPromocodes)
    {
        $this->findPromocodes = $findPromocodes;
    }

    public function __invoke(): Response
    {
        $promocodes = ($this->findPromocodes)();

        return $this->successJson($promocodes);
    }
}
