<?php

namespace App\Repository;

use App\Entity\Friend;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }


    public function getUserById($id)
    {
        $em = $this->getEntityManager();
        $user = $em->getRepository('App\Entity\User')->findBy(['id'=>$id]);

        if(!$user){
            throw new NotFoundHttpException('The user does not exist');
        }
        return $user;
    }

    public function getUserByUsername($username)
    {

        $user = $this->getEntityManager()->findBy(['username'=>$username]);
        if(!$user){
            throw new NotFoundHttpException('The user does not exist');
        }
        return $user;
    }

    public function getAllSenderUsersById($allRequests)
    {
        $userArray = [];
        /** @var Friend $request */
        foreach ($allRequests as $request){
            $result = $this->createQueryBuilder('user')
                ->where('user.id = :userid')
                ->select('user')
                ->setParameter('userid', $request->getSenderId())
                ->getQuery()
                ->getResult();
            array_push($userArray, $result[0]);
        }
        return $userArray;
    }

    public function setLastActiveForUser(User $user)
    {
        $now = date("Y-m-d H:i:s");
        $datetime = \DateTime::createFromFormat("Y-m-d H:i:s", $now);
        $user->setLastActive($datetime);
        $this->getEntityManager()->merge($user);
        $this->getEntityManager()->flush($user);

        return new Response('updated last active of '.$user->getId());
    }
    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
