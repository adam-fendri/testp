<?php

namespace App\Repository;

use App\Entity\Personne;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Personne>
 *
 * @method Personne|null find($id, $lockMode = null, $lockVersion = null)
 * @method Personne|null findOneBy(array $criteria, array $orderBy = null)
 * @method Personne[]    findAll()
 * @method Personne[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Personne::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Personne $entity, bool $flush = false): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Personne $entity, bool $flush = false): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

//    /**
//     * @return PersonneFixture[] Returns an array of PersonneFixture objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?PersonneFixture
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    private function addIntervaleAge(QueryBuilder $qb, $ageMin, $ageMax)
    {
        $qb->andWhere('p.age >= :ageMin and p.age <= :ageMax')
        ->setParameters(['ageMax'=>$ageMax,'ageMin'=>$ageMin]);
    }

    public function findPersonneByAgeInterval($ageMin,$ageMax)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.age >= :ageMin and p.age <= :ageMax')
           // ->setParameter('val', $value)
            ->setParameters(['ageMax'=>$ageMax,'ageMin'=>$ageMin])
            ->getQuery()
            ->getResult();

    }

    public function statsPersonneByAgeInterval($ageMin,$ageMax)
    {
        $qb= $this->createQueryBuilder('p')
            ->select('avg(p.age) as ageMoyen , count(p.id) as nbrePersonne');
            $this->addIntervaleAge($qb, $ageMin, $ageMax);
            return $qb->getQuery()->getScalarResult();

    }

}
