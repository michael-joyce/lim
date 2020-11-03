<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Repository;

use App\Entity\Occupation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Nines\UtilBundle\Repository\TermRepository;
use RuntimeException;

/**
 * @method null|Occupation find($id, $lockMode = null, $lockVersion = null)
 * @method null|Occupation findOneBy(array $criteria, array $orderBy = null)
 * @method Occupation[]    findAll()
 * @method Occupation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OccupationRepository extends TermRepository {
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Occupation::class);
    }

    /**
     * @return Query
     */
    public function indexQuery() {
        return $this->createQueryBuilder('occupation')
            ->orderBy('occupation.id')
            ->getQuery()
        ;
    }

    /**
     * @param string $q
     *
     * @return Collection|Occupation[]
     */
    public function typeaheadQuery($q) {
        $qb = $this->createQueryBuilder('occupation');
        $qb->andWhere('occupation.label LIKE :q');
        $qb->orderBy('occupation.label', 'ASC');
        $qb->setParameter('q', "{$q}%");

        return $qb->getQuery()->execute();
    }
}
