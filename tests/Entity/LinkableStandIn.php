<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\Entity;

use App\Entity\LinkableInterface;
use App\Entity\LinkableTrait;
use Nines\UtilBundle\Entity\AbstractEntity;

class LinkableStandIn extends AbstractEntity implements LinkableInterface {
    use LinkableTrait;

    public function __toString() : string {
        return '';
    }

    public function setId($id) : void {
        $this->id = $id;
    }
}
