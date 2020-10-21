<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Repository;

use App\Entity\ReferenceRole;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use RuntimeException;

/**
 * @method null|ReferenceRole find($id, $lockMode = null, $lockVersion = null)
 * @method null|ReferenceRole findOneBy(array $criteria, array $orderBy = null)
 * @method ReferenceRole[]    findAll()
 * @method ReferenceRole[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReferenceRoleRepository extends ServiceEntityRepository {
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, ReferenceRole::class);
    }

    /**
     * @return Query
     */
    public function indexQuery() {
        return $this->createQueryBuilder('referenceRole')
            ->orderBy('referenceRole.id')
            ->getQuery()
        ;
    }

    /**
     * @param string $q
     *
     * @return Collection|ReferenceRole[]
     */
    public function typeaheadQuery($q) {
        throw new RuntimeException('Not implemented yet.');
        $qb = $this->createQueryBuilder('referenceRole');
        $qb->andWhere('referenceRole.column LIKE :q');
        $qb->orderBy('referenceRole.column', 'ASC');
        $qb->setParameter('q', "{$q}%");

        return $qb->getQuery()->execute();
    }
}
