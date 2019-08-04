<?php

namespace App\Queries\FindProducts;

use App\Common\AppController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/product/list", methods={"GET"})
 */
final class FindProductsHttpAdapter extends AppController
{
    private $findProducts;

    public function __construct(FindProducts $findProducts)
    {
        $this->findProducts = $findProducts;
    }

    public function __invoke(Request $request): Response
    {
        $products = ($this->findProducts)();

        return $this->successJson($products);
    }
}
