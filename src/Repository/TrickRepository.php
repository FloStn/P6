<?php

namespace App\Repository;

use App\Entity\Trick;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @method Trick|null find($id, $lockMode = null, $lockVersion = null)
 * @method Trick|null findOneBy(array $criteria, array $orderBy = null)
 * @method Trick[]    findAll()
 * @method Trick[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrickRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Trick::class);
    }

//    /**
//     * @return Trick[] Returns an array of Trick objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Trick
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function countAllTricks()
    {
        $query = $this->createQueryBuilder('t')->select('COUNT(t)');

        return $query->getQuery()->getSingleScalarResult();
    }

    public function getPagination($first_result, $max_results)
    {
        $qb = $this->createQueryBuilder('t')
            ->select('t')
            ->setFirstResult($first_result)
            ->setMaxResults($max_results)
            ->orderBy('t.publishDate', 'DESC');

        $pag = new Paginator($qb);

        return $pag;
    }

    public function myFindOne($id)
    {
        $qb = $this->createQueryBuilder('t');

        $qb
        ->where('t.id = :id')
        ->setParameter('id', $id)
        ;

        return $qb
        ->getQuery()
        ->getResult()
        ;
    }
}
