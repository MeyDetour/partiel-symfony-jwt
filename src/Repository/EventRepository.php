<?php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Event>
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    public function getNextPublicEvent(): array
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.isPublic = :isPublic')
            ->andWhere('e.startDate > :now')
            ->andWhere('e.state != :canceled')
            ->setParameter('isPublic', true)
            ->setParameter('now', new \DateTime())
            ->setParameter('canceled', 'canceled')
            ->orderBy('e.startDate', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function searchInUser(string $keyword): array
    {
        $qb = $this->createQueryBuilder('e');

        return $qb->where(
            $qb->expr()->orX(
                $qb->expr()->like('e.name', ':keyword'),
                $qb->expr()->like('e.description', ':keyword')
            )
        )
            ->setParameter('keyword', '%' . $keyword . '%')
            ->getQuery()
            ->getResult();
    }




    //  /**
//     * @return Event[] Returns an array of Event objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Event
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
