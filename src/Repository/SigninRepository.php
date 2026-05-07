<?php

namespace App\Repository;

use App\Entity\Etudiant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

/**
 * @extends ServiceEntityRepository<Signin>
 *
 * @method Signin|null find($id, $lockMode = null, $lockVersion = null)
 * @method Signin|null findOneBy(array $criteria, array $orderBy = null)
 * @method Signin[]    findAll()
 * @method Signin[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SigninRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Etudiant::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Etudiant $etudiant, bool $flush = true): void
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
    public function remove(Signin $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // Add custom query methods below as needed.
}
