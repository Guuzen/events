<?php
declare(strict_types=1);

namespace App\Order\Action;

use App\Order\Model\Order;
use App\Promocode\NoPromocode;
use App\Promocode\Promocodes;
use App\Tariff\Tariffs;
use App\Infrastructure\Notifier\Notifier;
use App\User\Model\User;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;

final class CreateInvoiceHandler
{
    private $notifier;

    private $tariffs;

    private $promocodes;

    private $entityManager;

    public function __construct(
        Notifier $notifier,
        Tariffs $tariffs,
        Promocodes $promocodes,
        EntityManagerInterface $entityManager
    ) {
        $this->notifier = $notifier;
        $this->tariffs = $tariffs;
        $this->promocodes = $promocodes;
        $this->entityManager = $entityManager;
    }

    public function handle(CreateInvoice $createInvoice)
    {
        // TODO make a promocode factory ?
        if ('' === $createInvoice->getPromocode()) {
            $promocode = new NoPromocode();
        } else {
            $promocode = $this->promocodes->findByCode($createInvoice->getPromocode());
        }
        $tariff = $this->tariffs->getById(Uuid::fromString($createInvoice->getTariffId()));
        $sum = $tariff->applyPromocode($promocode);

        $user = User::createdInvoice($createInvoice); // TODO dependency from command
        $this->entityManager->persist($user);


        $invoice = Order::notPaid($sum, $user, $tariff, $promocode, $createInvoice->country);
        $this->entityManager->persist($invoice);

        // TODO call domain
        $this->notifier->notify([]);
    }
}
