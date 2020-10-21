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
use Nines\UtilBundle\Repository\TermRepository;
use RuntimeException;

/**
 * @method null|ReferenceRole find($id, $lockMode = null, $lockVersion = null)
 * @method null|ReferenceRole findOneBy(array $criteria, array $orderBy = null)
 * @method ReferenceRole[]    findAll()
 * @method ReferenceRole[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReferenceRoleRepository extends TermRepository {
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, ReferenceRole::class);
    }
}
