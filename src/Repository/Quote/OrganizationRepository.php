<?php

namespace App\Repository\Quote;

use App\Entity\Quote\Organization;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Organization>
 */
class OrganizationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Organization::class);
    }

    public function add(Organization $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Organization $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return array<Organization>
     */
    public function findOrdered(): array
    {
        return $this
            ->createQueryBuilder('organization')
            ->orderBy('organization.rank', Criteria::ASC)
            ->getQuery()
            ->getResult()
        ;
    }

    public function getFirstRank(): int
    {
        $query = $this
            ->createQueryBuilder('organization')
            ->select('MIN(organization.rank)')
            ->getQuery()
        ;

        /** @var int $minRank */
        $minRank = $query->getSingleScalarResult() ?? 0;

        return $minRank;
    }
}
