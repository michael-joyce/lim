<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Entity;

use App\Repository\LocationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractEntity;
use phpDocumentor\Reflection\Types\Collection;

/**
 * @ORM\Entity(repositoryClass=LocationRepository::class)
 * @ORM\Table(indexes={
 *     @ORM\Index(columns={"name"}, flags={"fulltext"}),
 * })
 */
class Location extends AbstractEntity implements ContributorInterface, LinkableInterface, ReferenceableInterface {
    use ContributorTrait {
        ContributorTrait::__construct as contributor_constructor;
    }

    use LinkableTrait {
        LinkableTrait::__construct as link_constructor;
    }

    use ReferenceableTrait {
        ReferenceableTrait::__construct as reference_constructor;
    }

    /**
     * @var string
     * @ORM\Column(type="string", length=200, nullable=false)
     */
    private $name;

    /**
     * @var int
     * @ORM\Column(type="integer", nullable=true)
     */
    private $geonameId;

    /**
     * @var float
     *
     * @ORM\Column(name="latitude", type="decimal", precision=10, scale=7, nullable=true)
     */
    private $latitude;

    /**
     * @var float
     *
     * @ORM\Column(name="longitude", type="decimal", precision=10, scale=7, nullable=true)
     */
    private $longitude;

    /**
     * @var string
     *
     * @ORM\Column(name="fclass", type="string", length=1, nullable=true)
     */
    private $fclass;

    /**
     * @var string
     *
     * @ORM\Column(name="fcode", type="string", length=10, nullable=true)
     */
    private $fcode;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=2, nullable=true)
     */
    private $country;

    /**
     * @var Collection|Person[]
     * @ORM\OneToMany(targetEntity="Person", mappedBy="birthPlace")
     */
    private $personsBorn;

    /**
     * @var Collection|Person[]
     * @ORM\OneToMany(targetEntity="Person", mappedBy="deathPlace")
     */
    private $personsDied;

    /**
     * @var Collection|Person[]
     * @ORM\ManyToMany(targetEntity="Person", mappedBy="homes")
     */
    private $residents;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    public function __construct() {
        parent::__construct();
        $this->contributor_constructor();
        $this->link_constructor();
        $this->reference_constructor();
        $this->personsBorn = new ArrayCollection();
        $this->personsDied = new ArrayCollection();
        $this->residents = new ArrayCollection();
    }

    /**
     * {@inheritdoc}
     */
    public function __toString() : string {
        return $this->name;
    }

    public function getName() : ?string {
        return $this->name;
    }

    public function setName(string $name) : self {
        $this->name = $name;

        return $this;
    }

    public function getGeonameId() : ?int {
        return $this->geonameId;
    }

    public function setGeonameId(?int $geonameId) : self {
        $this->geonameId = $geonameId;

        return $this;
    }

    public function getLatitude() : ?float {
        if (null !== $this->latitude) {
            return (float) $this->latitude;
        }

        return null;
    }

    public function setLatitude(?float $latitude) : self {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude() : ?float {
        if (null !== $this->longitude) {
            return (float) $this->longitude;
        }

        return null;
    }

    public function setLongitude(?float $longitude) : self {
        $this->longitude = $longitude;

        return $this;
    }

    public function getFclass() : ?string {
        return $this->fclass;
    }

    public function setFclass(?string $fclass) : self {
        $this->fclass = $fclass;

        return $this;
    }

    public function getFcode() : ?string {
        return $this->fcode;
    }

    public function setFcode(?string $fcode) : self {
        $this->fcode = $fcode;

        return $this;
    }

    public function getCountry() : ?string {
        return $this->country;
    }

    public function setCountry(?string $country) : self {
        $this->country = $country;

        return $this;
    }

    public function getDescription() : ?string {
        return $this->description;
    }

    public function setDescription(?string $description) : self {
        $this->description = $description;

        return $this;
    }
}
