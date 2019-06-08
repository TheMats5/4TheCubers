<?php

namespace App\Repository;

use App\Entity\Friend;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Friend|null find($id, $lockMode = null, $lockVersion = null)
 * @method Friend|null findOneBy(array $criteria, array $orderBy = null)
 * @method Friend[]    findAll()
 * @method Friend[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FriendsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Friend::class);
    }

    public function getAllFriends(User $user)
    {

        $results = $this->createQueryBuilder('friend')
            ->where('friend.receiver_id = :userId')
            ->Orwhere('friend.sender_id = :userId')
            ->Andwhere('friend.type = :accepted')
            ->setParameter('userId', $user->getId())
            ->setParameter('accepted', 'accepted')
            ->getQuery()
            ->getResult();

        return $results;

    }

    public function createFriendRequest(User $user, user $invitee)
    {
        $friend = new Friend();
        $friend->setSenderId($user->getId());
        $friend->setReceiverId($invitee->getId());
        $friend->setType('request_received');
        $this->saveFriend($friend);

    }

    public function saveFriend($friend)
    {
        $em = $this->getEntityManager();
        $em->persist($friend);
        $em->flush($friend);
    }

    public function getRequestedFriendships(User $user)
    {
        $results = $this->createQueryBuilder('friend')
            ->where('friend.type = :type')
            ->andWhere('friend.receiver_id = :userId' )
            ->setParameter('userId', $user->getId())
            ->setParameter('type', 'request_received')
            ->getQuery()
            ->getResult();
        return $results;


    }
    // /**
    //  * @return Friend[] Returns an array of Friend objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Friend
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
