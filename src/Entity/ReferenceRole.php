<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Entity;

use App\Repository\ReferenceRoleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractTerm;

/**
 * @ORM\Entity(repositoryClass=ReferenceRoleRepository::class)
 */
class ReferenceRole extends AbstractTerm {
    /**
     * @var Collection|Reference[]
     * @ORM\OneToMany(targetEntity="Reference", mappedBy="referenceRole")
     */
    private $references;

    public function __construct() {
        parent::__construct();
        $this->references = new ArrayCollection();
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
            $reference->setReferenceRole($this);
        }

        return $this;
    }

    public function removeReference(Reference $reference) : self {
        if ($this->references->contains($reference)) {
            $this->references->removeElement($reference);
            // set the owning side to null (unless already changed)
            if ($reference->getReferenceRole() === $this) {
                $reference->setReferenceRole(null);
            }
        }

        return $this;
    }
}
