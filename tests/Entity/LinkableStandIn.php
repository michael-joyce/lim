<?php


namespace App\Tests\Entity;

use App\Entity\LinkableInterface;
use App\Entity\LinkableTrait;
use Nines\UtilBundle\Entity\AbstractEntity;

class LinkableStandIn extends AbstractEntity implements LinkableInterface {
    use LinkableTrait;

    public function __toString() : string {
        return '';
    }

    public function setId($id) {
        $this->id = $id;
    }
}
