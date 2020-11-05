<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\Entity;

use App\Entity\ContributorInterface;
use App\Entity\ContributorTrait;
use Nines\UtilBundle\Entity\AbstractEntity;

class ContributorStandIn extends AbstractEntity implements ContributorInterface {
    use ContributorTrait;

    public function __toString() : string {
        return '';
    }
}
