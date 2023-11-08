<?php

namespace App\Repository\Quote;

use App\Entity\Quote\Service;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Service>
 */
class ServiceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Service::class);
    }

    public function add(Service $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Service $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return array<Service>
     */
    public function findOrdered(): array
    {
        return $this
            ->createQueryBuilder('service')
            ->orderBy('service.rank', Criteria::ASC)
            ->getQuery()
            ->getResult()
        ;
    }

    public function getFirstRank(): int
    {
        $query = $this
            ->createQueryBuilder('service')
            ->select('MIN(service.rank)')
            ->getQuery()
        ;

        /** @var int $minRank */
        $minRank = $query->getSingleScalarResult() ?? 0;

        return $minRank;
    }
}
