<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Entity;

use App\Repository\PersonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractEntity;

/**
 * @ORM\Entity(repositoryClass=PersonRepository::class)
 * @ORM\Table(indexes={
 *     @ORM\Index(name="person_sort_idx", columns={"sortable_name"}),
 *     @ORM\Index(name="person_search_idx", columns={"full_name", "biography"}, flags={"fulltext"})
 * })
 */
class Person extends AbstractEntity implements ContributorInterface, LinkableInterface, ReferenceableInterface {
    use ContributorTrait {
        ContributorTrait::__construct as contributor_constructor;
    }
    use LinkableTrait {
        LinkableTrait::__construct as link_constructor;
    }

    use ReferenceableTrait {
        ReferenceableTrait::__construct as reference_constructor;
    }

    public const MALE = 'm';

    public const FEMALE = 'f';

    public const UNKNOWN = 'u';

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $fullName;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $sortableName;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=1, nullable=false, options={"default": "U"})
     */
    private $gender;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    private $biography;

    /**
     * @var CircaDate
     * @ORM\OneToOne(targetEntity="CircaDate", cascade={"persist", "remove"})
     */
    private $birthYear;

    /**
     * @var CircaDate
     * @ORM\OneToOne(targetEntity="CircaDate", cascade={"persist", "remove"})
     */
    private $deathYear;

    /**
     * @var Location
     * @ORM\ManyToOne(targetEntity="Location", inversedBy="personsBorn")
     */
    private $birthPlace;

    /**
     * @var Location
     * @ORM\ManyToOne(targetEntity="Location", inversedBy="personsDied")
     */
    private $deathPlace;

    /**
     * @var Location
     * @ORM\ManyToMany(targetEntity="Location", inversedBy="residents")
     */
    private $homes;

    public function __construct() {
        parent::__construct();
        $this->contributor_constructor();
        $this->link_constructor();
        $this->reference_constructor();
        $this->homes = new ArrayCollection();
    }

    /**
     * {@inheritdoc}
     */
    public function __toString() : string {
        return $this->fullName;
    }

    public function getFullName() : ?string {
        return $this->fullName;
    }

    public function setFullName(string $fullName) : self {
        $this->fullName = $fullName;

        return $this;
    }

    public function getSortableName() : ?string {
        return $this->sortableName;
    }

    public function setSortableName(string $sortableName) : self {
        $this->sortableName = $sortableName;

        return $this;
    }

    public function getGender() : ?string {
        return $this->gender;
    }

    public function setGender(string $gender) : self {
        $this->gender = $gender;

        return $this;
    }

    public function getBiography() : ?string {
        return $this->biography;
    }

    public function setBiography(?string $biography) : self {
        $this->biography = $biography;

        return $this;
    }

    public function getBirthYear() : ?CircaDate {
        return $this->birthYear;
    }

    public function setBirthYear($birthYear) : self {
        if (is_string($birthYear) || is_numeric($birthYear)) {
            $this->birthYear = CircaDate::build($birthYear);
        } else {
            $this->birthYear = $birthYear;
        }

        return $this;
    }

    public function getDeathYear() : ?CircaDate {
        return $this->deathYear;
    }

    public function setDeathYear($deathYear) : self {
        if (is_string($deathYear) || is_numeric($deathYear)) {
            $this->deathYear = CircaDate::build($deathYear);
        } else {
            $this->deathYear = $deathYear;
        }

        return $this;
    }

    public function getBirthPlace() : ?Location {
        return $this->birthPlace;
    }

    public function setBirthPlace(?Location $birthPlace) : self {
        $this->birthPlace = $birthPlace;

        return $this;
    }

    public function getDeathPlace() : ?Location {
        return $this->deathPlace;
    }

    public function setDeathPlace(?Location $deathPlace) : self {
        $this->deathPlace = $deathPlace;

        return $this;
    }

    /**
     * @return Collection|Location[]
     */
    public function getHomes() : Collection {
        return $this->homes;
    }

    public function addHome(Location $home) : self {
        if ( ! $this->homes->contains($home)) {
            $this->homes[] = $home;
        }

        return $this;
    }

    public function removeHome(Location $home) : self {
        if ($this->homes->contains($home)) {
            $this->homes->removeElement($home);
        }

        return $this;
    }
}
