<?php

namespace App\Repository;

use App\Entity\Solve;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Solve|null find($id, $lockMode = null, $lockVersion = null)
 * @method Solve|null findOneBy(array $criteria, array $orderBy = null)
 * @method Solve[]    findAll()
 * @method Solve[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SolveRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Solve::class);
    }

    public function saveSolve(Solve $solve)
    {
        $em = $this->getEntityManager();
        $em->persist($solve);
        $em->flush($solve);
        return new Response('ok', 200);
    }

    public function addSolve(int $time,string $cubeType, User $user)
    {
        /** @var Solve $solve */
        $solve = new Solve;

        if(0 !== $time){
            $solve->setTime($time);
        }
        $solve->setType($cubeType);
        $solve->setUser($user);
        $this->saveSolve($solve);
        return $solve;
    }

    public function getAllSolvesByUser($user, $type)
    {
        if('' == $type){
            $results = $this->createQueryBuilder('solve')
                ->where('solve.user = :user')
                ->select('solve.time, solve.type')
                ->setParameter('user', $user)
                ->getQuery()
                ->getResult();
        } else {
            $results = $this->createQueryBuilder('solve')
                ->where('solve.user = :user')
                ->andWhere('solve.type LIKE :type')
                ->select('solve.time, solve.type')
                ->setParameter('user', $user)
                ->setParameter('type', $type)
                ->getQuery()
                ->getResult();
        }

        if(count($results) > 0){
            foreach($results as $result){
                $rows[] = $result;
            }
        } else {
            $rows = null;
        }

        return $rows;
    }

    // /**
    //  * @return Solve[] Returns an array of Solve objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Solve
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
