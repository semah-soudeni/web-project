<?php

namespace App\Repository;

use App\Entity\Events;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class EventsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Events::class);
    }

    public function findByClub(string $club): array
    {
        $qb = $this->createQueryBuilder('e')
            ->leftJoin('e.clubs', 'c')
            ->addSelect('c')
            ->orderBy('e.eventDate', 'ASC')
            ->addOrderBy('e.eventTime', 'ASC');

        if ($club !== 'all') {
            $qb->where('c.slug = :slug')
                ->setParameter('slug', $club);
        }

        return $qb->getQuery()->getResult();
    }
    public function findByTitle(string $title): Events
    {
        return $this->createQueryBuilder("e")
                    ->andWhere("e.title = :val")
                    ->setParameter("val",$title)
                    ->getQuery()
                    ->getOneOrNullResult();
    }
}
