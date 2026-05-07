<?php

namespace App\Repository;

use App\Entity\Etudiant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Signup>
 *
 * @method Signup|null find($id, $lockMode = null, $lockVersion = null)
 * @method Signup|null findOneBy(array $criteria, array $orderBy = null)
 * @method Signup[]    findAll()
 * @method Signup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SignupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Signup::class);
    }

    public function add(Signup $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function remove(Signup $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // Example custom query methods:
    // /**
    //  * @return Signup[] Returns an array of Signup objects
    //  */
    // public function findByExampleField($value): array
    // {
    //     return $this->createQueryBuilder('s')
    //         ->andWhere('s.exampleField = :val')
    //         ->setParameter('val', $value)
    //         ->orderBy('s.id', 'ASC')
    //         ->setMaxResults(10)
    //         ->getQuery()
    //         ->getResult();
    // }

    // public function findOneBySomeField($value): ?Signup
    // {
    //     return $this->createQueryBuilder('s')
    //         ->andWhere('s.someField = :val')
    //         ->setParameter('val', $value)
    //         ->getQuery()
    //         ->getOneOrNullResult();
    // }
}
