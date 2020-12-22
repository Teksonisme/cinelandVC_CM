<?php

namespace App\Repository;

use App\Entity\Film;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Film|null find($id, $lockMode = null, $lockVersion = null)
 * @method Film|null findOneBy(array $criteria, array $orderBy = null)
 * @method Film[]    findAll()
 * @method Film[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FilmRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Film::class);
    }

    // /**
    //  * @return Film[] Returns an array of Film objects
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
    public function findOneBySomeField($value): ?Film
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    /**
     * @return Film[]
     */
    public function findBetweenTwoYears(int $year1, int $year2): array
    {
        $eM = $this->getEntityManager();
        if ($year1 > $year2) {
            $year = $year2;
            $year2 = $year1;
            $year1 = $year;
        }
        $qb = $eM->createQueryBuilder()
        ->select('f')
        ->from('App\Entity\Film','f')
        ->where('YEAR(f.dateSortie) > :year1 - 1')
        ->andwhere('YEAR(f.dateSortie) < :year2 + 1')
        ->setParameters(['year1' => $year1, 'year2' => $year2])
        ;

        return $qb->getQuery()->getResult();
    }
    public function findBeforeOneYear(int $year): array
    {
        $eM = $this->getEntityManager();
        $qb = $eM->createQueryBuilder()
        ->select('f')
        ->from('App\Entity\Film','f')
        ->where('YEAR(f.dateSortie) < :year')
        ->setParameter('year', $year)
        ;

        return $qb->getQuery()->getResult();
    }


}
