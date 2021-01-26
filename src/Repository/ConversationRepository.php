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

    public function findOneByParticipants(User $me, User $other)
    {
        $qb =  $this->createQueryBuilder('c');
  
        $qb->join('c.users', 'users', 'WITH', $qb->expr()->andX(
            $qb->expr()->in('users', $me->getId()),
            $qb->expr()->in('users', $other->getId())
        ));

        return $qb->getQuery()->getResult();
    }

    /**
     * @return Conversation[]
     */
    public function findLast15ConvsOfUser(User $user, int $limit = 15): array
    {
        $qb = $this->createQueryBuilder('c');
        // "SELECT c FROM App\Entity\Conversation c INNER JOIN c.users users WITH users IN(1)"
        // "SELECT c FROM App\Entity\Conversation c INNER JOIN c.users users WITH 1 IN(users)"
        $qb->join('c.users', 'users', 'WITH', $qb->expr()->in('users', $user->getId()));
        //$qb->join('c.users', 'users', 'WITH', "{$user->getId()} IN users");


        $qb->setMaxResults($limit);

        $qb->orderBy('c.updatedAt', 'DESC');

        return $qb->getQuery()->getResult();
    }

    /*
     *  // Example - $qb->expr()->in('u.id', array(1, 2, 3))
    // Make sure that you do NOT use something similar to $qb->expr()->in('value', array('stringvalue')) 
    // as this will cause Doctrine to throw an Exception.
    // Instead, use $qb->expr()->in('value', array('?1')) and bind your parameter to ?1 (see section above)
    //   public function in($x, $y); // Returns Expr\Func instance
     */
}
