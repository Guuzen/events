<?php

namespace App\Infrastructure\DomainEvent;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\Messenger\MessageBusInterface;

final class DomainEventSubscriber implements EventSubscriber
{
    private $dispatcher;

    public function __construct(MessageBusInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::postFlush,
        ];
    }

    public function postFlush(PostFlushEventArgs $postFlushEventArgs): void
    {
        $em  = $postFlushEventArgs->getEntityManager();
        $uow = $em->getUnitOfWork();
        /** @var array<int, array<int, Entity|object>> $identityMap */
        $identityMap = $uow->getIdentityMap();

        foreach ($identityMap as $entities) {
            foreach ($entities as $entity) {
                // TODO remove entity check when all entity extend base entity
                if (!$entity instanceof Entity) {
                    continue;
                }
                $events = $entity->releaseEvents();
                foreach ($events as $event) {
                    $this->dispatcher->dispatch($event);
                }
            }
        }
    }
}
