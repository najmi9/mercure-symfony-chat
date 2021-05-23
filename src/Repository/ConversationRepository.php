<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use App\Entity\Conversation;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Conversation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Conversation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Conversation[]    findAll()
 * @method Conversation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConversationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Conversation::class);
    }

    /**
     * @return Conversation[]
     */
    public function findConvsOfUser(User $user, int $limit = 0, int $offset = 0): array
    {
        $qb = $this->createQueryBuilder('c');
        $qb->join('c.users', 'users', 'WITH', $qb->expr()->in('users', $user->getId()));
        
        if ($limit > 0) {
            $qb->setMaxResults($limit);
        }

        $qb->orderBy('c.updatedAt', 'DESC')
            ->setFirstResult($offset)
        ;

        return $qb->getQuery()->getResult();
    }
}
