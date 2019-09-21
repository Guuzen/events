<?php

namespace App\Infrastructure\DomainEvent;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final class DoctrineNotificationSubscriber implements EventSubscriber
{
    private $eventDispatcher;

    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::postFlush,
        ];
    }

    public function postFlush(PostFlushEventArgs $postFlushEventArgs): void
    {
        $em          = $postFlushEventArgs->getEntityManager();
        $uow         = $em->getUnitOfWork();
        /** @var Entity[][] $identityMap */
        $identityMap = $uow->getIdentityMap();

        foreach ($identityMap as $entities) {
            foreach ($entities as $entity) {
                // TODO remove entity check when all entity extend base entity
                /** @psalm-suppress DocblockTypeContradiction */
                if (!$entity instanceof Entity) {
                    continue;
                }
                $events = $entity->releaseEvents();
                foreach ($events as $event) {
                    $this->eventDispatcher->dispatch($event);
                }
            }
        }
    }
}
