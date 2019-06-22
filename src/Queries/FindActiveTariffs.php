<?php
declare(strict_types=1);

namespace App\Queries;

use App\Common\AppController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class FindActiveTariffs extends AppController
{
    /**
     * @Route("/")
     */
    public function all(Request $request): Response
    {
        return $this->render('base.html.twig');
    }
}
