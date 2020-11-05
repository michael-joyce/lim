<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Service;

use App\Entity\ContributorInterface;
use DateTime;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Nines\UserBundle\Entity\User;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Security;

/**
 * Commenting service for Symfony.
 */
class ContributionManager implements EventSubscriber {
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var Security
     */
    private $security;

    public function addContributor($entity) : void {
        if ( ! $entity instanceof ContributorInterface) {
            return;
        }
        /** @var User $user */
        $user = $this->security->getUser();
        if ( ! $user) {
            return;
        }
        $entity->addContribution(new DateTime(), $user->getFullname());
    }

    /**
     * @required
     */
    public function setEntityManager(EntityManagerInterface $em) : void {
        $this->em = $em;
    }

    /**
     * @required
     */
    public function setSecurity(Security $security) : void {
        $this->security = $security;
    }

    /**
     * @required
     */
    public function setLogger(LoggerInterface $logger) : void {
        $this->logger = $logger;
    }

    public function getSubscribedEvents() {
        return [
            Events::preUpdate,
            Events::prePersist,
        ];
    }

    public function preUpdate(LifecycleEventArgs $args) : void {
        $this->addContributor($args->getEntity());
    }

    public function prePersist(LifecycleEventArgs $args) : void {
        $this->addContributor($args->getEntity());
    }
}
