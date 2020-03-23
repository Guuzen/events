<?php

namespace App\User\Model;

use App\Common\Error;
use App\User\Model\Error\UserNotFound;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

final class Users extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function add(User $user): void
    {
        $this->_em->persist($user);
    }

    /**
     * @return User|Error
     */
    public function findById(UserId $userId)
    {
        $query = $this->_em->createQuery('
            select
                u
            from
                App\User\Model\User as u
            where
                u.id = :user_id
        ');
        $query->setParameter('user_id', $userId);

        /** @var User|null */
        $user = $query->getOneOrNullResult();
        if (null === $user) {
            return new UserNotFound();
        }

        return $user;
    }
}
