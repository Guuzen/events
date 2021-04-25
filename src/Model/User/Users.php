<?php

namespace App\Model\User;

use App\Model\User\Exception\UserLoadFailed;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

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
        $query = $this->createQueryBuilder('u')
            ->where('u.id = :user_id')
            ->getQuery();
        $query->setParameter('user_id', $userId);

        try {
            /** @var User $user */
            $user = $query->getSingleResult();
        } catch (\Throwable $exception) {
            throw new UserLoadFailed('', 0, $exception);
        }

        return $user;
    }
}
