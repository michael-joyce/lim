<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\PersonRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractEntity;

/**
 * @ORM\Entity(repositoryClass=PersonRepository::class)
 */
class Person extends AbstractEntity implements LinkableInterface, ReferenceableInterface {

    public const MALE = 'm';

    public const FEMALE = 'f';

    use LinkableTrait {
        LinkableTrait::__construct as link_constructor;
    }

    use ReferenceableTrait {
        ReferenceableTrait::__construct as reference_constructor;
    }

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
     * @ORM\Column(type="string", length=1, nullable=false, options={"default"="U"})
     */
    private $gender;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    private $biography;

    /**
     * @var CircaDate
     * @ORM\OneToOne(targetEntity="CircaDate")
     */
    private $birthYear;

    /**
     * @var CircaDate
     * @ORM\OneToOne(targetEntity="CircaDate")
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

    /**
     * @var Occupation[]|Collection
     * @ORM\ManyToMany(targetEntity="Occupation", inversedBy="persons")
     */
    private $occupations;

    /**
     * @inheritDoc
     */
    public function __toString() : string {
        return $this->fullName;
    }

    public function __construct() {
        parent::__construct();
        $this->link_constructor();
        $this->reference_constructor();
    }

}
