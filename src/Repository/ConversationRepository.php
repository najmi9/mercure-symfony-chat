<?php

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

    // /**
    //  * @return Conversation[] Returns an array of Conversation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /**
     * @param User[] $users
     */
    public function findOneByParticipants(array $users): ?Conversation
    {
        // Every convesation is defined by his users
        // The main goal is to find a conversation by his users
        $qb =  $this->createQueryBuilder('c');
        $qb
        ->join('c.users', 'p')
        ->having('p = (:users)')
        ->setParameter('users', $users[0])
        ;
        dd($qb->getQuery()->getResult());
        return $qb->getQuery()
        ->getOneOrNullResult();
    }

    public function findByUser(User $user): array
    {
        // Each user can have many conversations
        // Each conversation can have many users
        // The main goal is to find all convs by the current user.
        $qb = $this->createQueryBuilder('c');
        $qb = $qb
            ->leftJoin('c.users', 'users')
            ->leftJoin('c.lastMessage', 'm')
            ->groupBy('users')
            ->where('users = :user')->setParameter('user', $user)
            //->having()
            //->where($qb->expr()->in('users', ':user'))->setParameter('user', $user)
            ->select('c.id', 'c.createdAt', 'm.content', 'users.email')
            ->getQuery()
            ->getResult()
        ;
        return $qb;
    }

    /**
     * @return Conversation[]
     */
    public function findAllConvsOfUser(User $user, int $limit = 15): array
    {
        return [];
    }
}
