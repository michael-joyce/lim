<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Repository;

use App\Entity\Location;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method null|Location find($id, $lockMode = null, $lockVersion = null)
 * @method null|Location findOneBy(array $criteria, array $orderBy = null)
 * @method Location[]    findAll()
 * @method Location[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LocationRepository extends ServiceEntityRepository {
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Location::class);
    }

    public function indexQuery() {
        return $this->createQueryBuilder('location')->orderBy('location.id')->getQuery();
    }

    /**
     * @return Collection|Location[]
     */
    public function typeaheadQuery(string $q) {
        $qb = $this->createQueryBuilder('location');
        $qb->andWhere('location.name LIKE :q');
        $qb->orderBy('location.name', 'ASC');
        $qb->setParameter('q', "{$q}%");

        return $qb->getQuery()->execute();
    }

    public function searchQuery(string $q) {
        $qb = $this->createQueryBuilder('location');
        $qb->addSelect('MATCH (location.name) AGAINST(:q BOOLEAN) as HIDDEN score');
        $qb->andHaving('score > 0');
        $qb->orderBy('score', 'DESC');
        $qb->setParameter('q', $q);

        return $qb->getQuery();
    }
}
