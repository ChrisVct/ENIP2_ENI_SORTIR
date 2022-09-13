<?php

namespace App\Repository;

use App\Entity\Event;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Event>
 *
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    public function getEventsAsOrganizer(User $fakeUser)
    {
        $queryBuilder = $this->createQueryBuilder('e')
            ->andWhere('e.organizer = :fakeUser')
            ->setParameter('fakeUser', $fakeUser)
            ->andWhere('e.startAt > :now ')
            ->setParameter('now', new \DateTime('now'))
            ->orderBy('e.startAt','ASC');
        $query=$queryBuilder->getQuery();
        $result = $query->getResult();
        return $result;

    }
    public function getEventsWhereRegistred(User $fakeUser)
    {
        $queryBuilder = $this->createQueryBuilder('e')
            ->join('e.registration','u')
            ->addSelect('u')
            ->andWhere('u = :fakeUser')
            ->setParameter('fakeUser', $fakeUser)
            ->andWhere('e.startAt > :now ')
            ->setParameter('now', new \DateTime('now'));
        $query=$queryBuilder->getQuery();
        $result = $query->getResult();
        return $result;
    }
    public function getEventsWhereNotRegistred(?User $fakeUser)
    {
        $queryBuilder = $this->createQueryBuilder('e')
            ->join('e.registration','u')
            ->addSelect('u')
            ->andWhere('u != :fakeUser')
            ->setParameter('fakeUser', $fakeUser)
            ->andWhere('e.startAt > :now ')
            ->setParameter('now', new \DateTime('now'));
        $query=$queryBuilder->getQuery();
        $result = $query->getResult();
        return $result;
    }
    public function add(Event $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Event $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }




}
