<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Repository;

use App\Entity\Work;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use RuntimeException;

/**
 * @method null|Work find($id, $lockMode = null, $lockVersion = null)
 * @method null|Work findOneBy(array $criteria, array $orderBy = null)
 * @method Work[]    findAll()
 * @method Work[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WorkRepository extends ServiceEntityRepository {
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Work::class);
    }

    /**
     * @return Query
     */
    public function indexQuery() {
        return $this->createQueryBuilder('work')
            ->orderBy('work.id')
            ->getQuery()
        ;
    }

    /**
     * @param string $q
     *
     * @return Collection|Work[]
     */
    public function typeaheadQuery($q) {
        $qb = $this->createQueryBuilder('work');
        $qb->andWhere('work.title LIKE :q');
        $qb->orderBy('work.title', 'ASC');
        $qb->setParameter('q', "{$q}%");

        return $qb->getQuery()->execute();
    }
}
