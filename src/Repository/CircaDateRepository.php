<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\CircaDate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CircaDate|null find($id, $lockMode = null, $lockVersion = null)
 * @method CircaDate|null findOneBy(array $criteria, array $orderBy = null)
 * @method CircaDate[]    findAll()
 * @method CircaDate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CircaDateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CircaDate::class);
    }

    /**
     * @return Query
     */
    public function indexQuery() {
        return $this->createQueryBuilder('circaDate')
            ->orderBy('circaDate.id')
            ->getQuery();
    }

    /**
     * @param string $q
     *
     * @return Collection|CircaDate[]
     */
    public function typeaheadQuery($q) {
        throw new \RuntimeException("Not implemented yet.");
        $qb = $this->createQueryBuilder('circaDate');
        $qb->andWhere('circaDate.column LIKE :q');
        $qb->orderBy('circaDate.column', 'ASC');
        $qb->setParameter('q', "{$q}%");

        return $qb->getQuery()->execute();
    }

            
}
