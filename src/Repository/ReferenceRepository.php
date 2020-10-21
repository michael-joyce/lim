<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Repository;

use App\Entity\Reference;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use RuntimeException;

/**
 * @method null|Reference find($id, $lockMode = null, $lockVersion = null)
 * @method null|Reference findOneBy(array $criteria, array $orderBy = null)
 * @method Reference[]    findAll()
 * @method Reference[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReferenceRepository extends ServiceEntityRepository {
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Reference::class);
    }

    /**
     * @return Query
     */
    public function indexQuery() {
        return $this->createQueryBuilder('reference')
            ->orderBy('reference.id')
            ->getQuery()
        ;
    }

    /**
     * @param string $q
     *
     * @return Collection|Reference[]
     */
    public function typeaheadQuery($q) {
        throw new RuntimeException('Not implemented yet.');
        $qb = $this->createQueryBuilder('reference');
        $qb->andWhere('reference.column LIKE :q');
        $qb->orderBy('reference.column', 'ASC');
        $qb->setParameter('q', "{$q}%");

        return $qb->getQuery()->execute();
    }
}
