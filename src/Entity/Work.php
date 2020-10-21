<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Entity;

use App\Repository\WorkRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractEntity;

/**
 * @ORM\Entity(repositoryClass=WorkRepository::class)
 */
class Work extends AbstractEntity implements LinkableInterface {
    use LinkableTrait {
        LinkableTrait::__construct as link_constructor;
    }

    /**
     * @var string
     * @ORM\Column(type="text", nullable=false)
     */
    private $title;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=false)
     */
    private $citation;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    private $summary;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    private $location;

    /**
     * @var Collection|Reference[]
     * @ORM\OneToMany(targetEntity="Reference", mappedBy="work")
     */
    private $references;

    public function __construct() {
        parent::__construct();
        $this->link_constructor();
        $this->references = new ArrayCollection();
    }

    /**
     * {@inheritdoc}
     */
    public function __toString() : string {
        return $this->title;
    }

    public function getTitle() : ?string {
        return $this->title;
    }

    public function setTitle(string $title) : self {
        $this->title = $title;

        return $this;
    }

    public function getCitation() : ?string {
        return $this->citation;
    }

    public function setCitation(string $citation) : self {
        $this->citation = $citation;

        return $this;
    }

    public function getSummary() : ?string {
        return $this->summary;
    }

    public function setSummary(?string $summary) : self {
        $this->summary = $summary;

        return $this;
    }

    public function getLocation() : ?string {
        return $this->location;
    }

    public function setLocation(string $location) : self {
        $this->location = $location;

        return $this;
    }

    /**
     * @return Collection|Reference[]
     */
    public function getReferences() : Collection {
        return $this->references;
    }

    public function addReference(Reference $reference) : self {
        if ( ! $this->references->contains($reference)) {
            $this->references[] = $reference;
            $reference->setWork($this);
        }

        return $this;
    }

    public function removeReference(Reference $reference) : self {
        if ($this->references->contains($reference)) {
            $this->references->removeElement($reference);
            // set the owning side to null (unless already changed)
            if ($reference->getWork() === $this) {
                $reference->setWork(null);
            }
        }

        return $this;
    }
}
