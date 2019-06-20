<?php

namespace App\Repository;

use App\Entity\Solve;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
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

    public function addSolve(int $time,string $cubeType,string $scramble,?bool $plus2, User $user)
    {
        /** @var Solve $solve */
        $solve = new Solve;

        if(0 !== $time){
            $solve->setTime($time);
        }
        if (null===$plus2){
            $solve->setPlus2(0);
        } else {
            $solve->setPlus2(1);
        }
        $solve->setCreated_at(new \DateTime());
        $solve->setType($cubeType);
        $solve->setScramble($scramble);
        $solve->setUser($user);
        $this->saveSolve($solve);
        return $solve;
    }

    public function getAllSolvesByUser($user)
    {
        $results = $this->createQueryBuilder('solve')
            ->where('solve.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
        return $results;
    }
    public function getAllSolvesByUserAndType($user, $type)
    {
        if('' == $type){
            $type = '333';
            $results = $this->createQueryBuilder('solve')
                ->where('solve.user = :user')
                ->select('solve.time, solve.type')
                ->setParameter('user', $user)
                ->setParameter('type', $type)
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

    public function getAllSolvesCountByUser($user, $type)
    {
        $results = $this->createQueryBuilder('solve')
            ->where('solve.user = :user')
            ->andWhere('solve.time IS NOT NULL' )
            ->andWhere("solve.type = :type")
            ->select('count(solve.id)')
            ->setParameter('user', $user)
            ->setParameter('type', $type)
            ->getQuery()
            ->getSingleScalarResult();

        return $results;


    }

    public function getAllPlus2CountByUser($user, $type)
    {
        $results = $this->createQueryBuilder('solve')
            ->where('solve.user = :user')
            ->andWhere('solve.plus2 = 1' )
            ->andWhere("solve.type = :type")
            ->select('count(solve.id)')
            ->setParameter('user', $user)
            ->setParameter('type', $type)
            ->getQuery()
            ->getSingleScalarResult();

        return $results;
    }

    public function getAllDnfCountByUser($user, $type)
    {
        $results = $this->createQueryBuilder('solve')
            ->where('solve.user = :user')
            ->andWhere('solve.time IS NULL' )
            ->andWhere("solve.type = :type")
            ->select('count(solve.id)')
            ->setParameter('user', $user)
            ->setParameter('type', $type)
            ->getQuery()
            ->getSingleScalarResult();

        return $results;
    }

    public function getBestSolve($user, $type)
    {
        $result = $this->createQueryBuilder('solve')
            ->where('solve.user = :user')
            ->andWhere('solve.time IS NOT NULL' )
            ->andWhere("solve.type = :type")
            ->select('solve.time')
            ->OrderBy('solve.time', 'ASC')
            ->setParameter('user', $user)
            ->setParameter('type', $type)
            ->getQuery()
            ->getResult();

        if(!reset($result)){
            $result=0;
        } else{
            $result = reset($result);
            $result = current($result);
        }
        return $result;

    }

    public function getAverageOFLatestSolves($user, $type, $averageOf)
    {
        $results = $this->createQueryBuilder('solve')
            ->where('solve.user = :user')
            ->andWhere('solve.time IS NOT NULL' )
            ->andWhere("solve.type = :type")
            ->select('solve')
            ->OrderBy('solve.created_at', 'DESC')
            ->setParameter('user', $user)
            ->setParameter('type', $type)
            ->getQuery()
            ->getResult();
        if(count($results)<$averageOf){
            $average = 0;
        } else {
            $results = array_slice($results, 0, $averageOf, true);
            $sum = 0;
            /** @var Solve $solve */
            foreach($results as $solve){

                $sum += $solve->getTime();
            }
            if(count($results) === 0){
                $average = 0;
            } else {
                $average = $sum/count($results);
            }
        }
        return floor($average);
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
