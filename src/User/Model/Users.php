<?php

namespace App\User\Model;

use App\User\Model\Exception\UserNotFound;
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

    public function findById(UserId $userId): User
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

        try {
            /** @var User $user */
            $user = $query->getSingleResult();
        } catch (\Throwable $exception) {
            throw new UserNotFound('', 0, $exception);
        }

        return $user;
    }
}
