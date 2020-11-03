<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Repository;

use App\Entity\Occupation;
use Doctrine\Persistence\ManagerRegistry;
use Nines\UtilBundle\Repository\TermRepository;

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
}
