<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Entity;

use App\Repository\OccupationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractTerm;

/**
 * @ORM\Entity(repositoryClass=OccupationRepository::class)
 */
class Occupation extends AbstractTerm {
    /**
     * @var Collection|Person[]
     * @ORM\ManyToMany(targetEntity="Person", mappedBy="occupations")
     */
    private $persons;

    public function __construct() {
        parent::__construct();
        $this->persons = new ArrayCollection();
    }

    /**
     * @return Collection|Person[]
     */
    public function getPersons() : Collection {
        return $this->persons;
    }

    public function addPerson(Person $person) : self {
        if ( ! $this->persons->contains($person)) {
            $this->persons[] = $person;
            $person->addOccupation($this);
        }

        return $this;
    }

    public function removePerson(Person $person) : self {
        if ($this->persons->contains($person)) {
            $this->persons->removeElement($person);
            $person->removeOccupation($this);
        }

        return $this;
    }
}
