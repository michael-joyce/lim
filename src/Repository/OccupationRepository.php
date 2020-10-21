<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Occupation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Occupation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Occupation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Occupation[]    findAll()
 * @method Occupation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OccupationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Occupation::class);
    }

    /**
     * @return Query
     */
    public function indexQuery() {
        return $this->createQueryBuilder('occupation')
            ->orderBy('occupation.id')
            ->getQuery();
    }

    /**
     * @param string $q
     *
     * @return Collection|Occupation[]
     */
    public function typeaheadQuery($q) {
        throw new \RuntimeException("Not implemented yet.");
        $qb = $this->createQueryBuilder('occupation');
        $qb->andWhere('occupation.column LIKE :q');
        $qb->orderBy('occupation.column', 'ASC');
        $qb->setParameter('q', "{$q}%");

        return $qb->getQuery()->execute();
    }

    
}
