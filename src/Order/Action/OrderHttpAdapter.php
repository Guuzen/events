<?php

namespace App\Order\Action;

use App\Infrastructure\Http\AppController;
use App\User\Action\CreateUser;
use App\User\Action\UserHandler;
use App\User\Model\Contacts;
use App\User\Model\FullName;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class OrderHttpAdapter extends AppController
{
    private $orderHandler;

    private $userHandler;

    private $em;

    public function __construct(OrderHandler $orderHandler, UserHandler $userHandler, EntityManagerInterface $em)
    {
        $this->orderHandler = $orderHandler;
        $this->userHandler  = $userHandler;
        $this->em           = $em;
    }

    /**
     * @Route("/order/place", methods={"POST"})
     */
    public function placeOrder(PlaceOrderRequest $placeOrderRequest): Response
    {
        // TODO create user must be idempotent. Maybe this method should be named in other way
        $createUser = new CreateUser(
            new FullName($placeOrderRequest->firstName, $placeOrderRequest->lastName),
            new Contacts($placeOrderRequest->email, $placeOrderRequest->phone)
        );
        $userId     = $this->userHandler->createUser($createUser);
        $this->em->flush();

        $placeOrder = new PlaceOrder($placeOrderRequest->tariffId, $placeOrderRequest->eventId, (string)$userId);
        $orderId    = $this->orderHandler->placeOrder($placeOrder);
        $this->em->flush();

        return $this->response($orderId);
    }

    /**
     * @Route("/admin/order/markPaid", methods={"POST"})
     */
    public function markOrderPaid(MarkOrderPaid $markOrderPaid): Response
    {
        $result = $this->orderHandler->markOrderPaid($markOrderPaid);

        return $this->response($result);
    }

    /**
     * @Route("/order/payByCard", methods={"POST"})
     */
    public function payByCard(PayByCard $payByCard): Response
    {
        $paymentUrl = $this->orderHandler->payByCard($payByCard);

        return $this->response($paymentUrl);
    }

    /**
     * @Route("/order/markPaidByFondy", methods={"POST"})
     */
    public function markOrdePaidByFondy(MarkOrderPaidByFondy $markOrderPaidByFondy): Response
    {
        $result = $this->orderHandler->markOrderPaidByFondy($markOrderPaidByFondy);

        return $this->response($result);
    }
}
