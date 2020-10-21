<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Entity;

use App\Repository\ReferenceRepository;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Nines\UtilBundle\Entity\AbstractEntity;

/**
 * @ORM\Entity(repositoryClass=ReferenceRepository::class)
 * @ORM\Table(indexes={
 *  @ORM\Index(columns={"entity"})
 * })
 */
class Reference extends AbstractEntity {
    /**
     * @var string
     * @ORM\Column(type="string", length=128, nullable=false)
     */
    private $entity;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @var ReferenceRole
     * @ORM\ManyToOne(targetEntity="ReferenceRole", inversedBy="references")
     */
    private $referenceRole;

    /**
     * @var Work
     * @ORM\ManyToOne(targetEntity="Work", inversedBy="references")
     */
    private $work;

    public function __construct() {
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    public function __toString() : string {
        return $this->work->__toString();
    }

    public function setEntity(AbstractEntity $entity) : void {
        if ( ! $entity->getId()) {
            throw new Exception('Reference entities must be persisted.');
        }
        $this->entity = get_class($entity) . ':' . $entity->getId();
    }

    public function getEntity() : ?string {
        return $this->entity;
    }

    public function getDescription() : ?string {
        return $this->description;
    }

    public function setDescription(?string $description) : self {
        $this->description = $description;

        return $this;
    }

    public function getReferenceRole() : ?ReferenceRole {
        return $this->referenceRole;
    }

    public function setReferenceRole(?ReferenceRole $referenceRole) : self {
        $this->referenceRole = $referenceRole;

        return $this;
    }

    public function getWork() : ?Work {
        return $this->work;
    }

    public function setWork(?Work $work) : self {
        $this->work = $work;

        return $this;
    }
}
