<?php

namespace App\Repository;

use App\Entity\Etudiant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class EtudiantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Etudiant::class);
    }

    public function findByEmail($email){
        return $this->createQueryBuilder("e")
                    ->andWhere("e.email = :val")
                    ->setParameter('val' , $email)
                    ->getQuery()
                    ->getOneOrNullResult();
    
    }
}
