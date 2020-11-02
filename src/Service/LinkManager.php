<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Service;

use App\Entity\Link;
use App\Entity\LinkableInterface;
use App\Repository\LinkRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Exception;
use Nines\UtilBundle\Entity\AbstractEntity;
use Psr\Log\LoggerInterface;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Commenting service for Symfony.
 */
class LinkManager implements EventSubscriber {
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var UrlGeneratorInterface
     */
    private $router;

    /**
     * Mapping of class name to route name.
     *
     * @var array
     */
    private $routing;

    /**
     * @var LinkRepository
     */
    private $linkRepository;

    /**
     * Build the commenting service.
     *
     * @param array $routing
     */
    public function __construct($routing) {
        $this->routing = $routing;
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
    public function setLogger(LoggerInterface $logger) : void {
        $this->logger = $logger;
    }

    /**
     * @required
     */
    public function setRouter(UrlGeneratorInterface $router) : void {
        $this->router = $router;
    }

    /**
     * @required
     */
    public function setLinkRepository(LinkRepository $linkRepository) : void {
        $this->linkRepository = $linkRepository;
    }

    /**
     * Check if an entity is configured to accept links.
     *
     * @param AbstractEntity $entity
     *
     * @return bool
     */
    public function acceptsLinks($entity) {
        return $entity instanceof LinkableInterface;
    }

    /**
     * Find the entity corresponding to a comment.
     *
     * @return mixed
     */
    public function findEntity(Link $link) {
        [$class, $id] = explode(':', $link->getEntity());

        return $this->em->getRepository($class)->find($id);
    }

    /**
     * Return the short class name for the entity a link refers to.
     */
    public function entityType(Link $link) : ?string {
        $entity = $this->findEntity($link);
        if ( ! $entity) {
            return null;
        }

        try {
            $reflection = new ReflectionClass($entity);
        } catch (ReflectionException $e) {
            $this->logger->error('Cannot find entity for link ' . $link->getEntity());

            return null;
        }

        return $reflection->getShortName();
    }

    /**
     * Find the links for an entity.
     *
     * @param mixed $entity
     *
     * @return Collection|Link[]
     */
    public function findLinks($entity) {
        $class = get_class($entity);

        return $this->linkRepository->findBy([
            'entity' => $class . ':' . $entity->getId(),
        ]);
    }

    /**
     * Add a link to an entity.
     *
     * @param mixed $entity
     *
     * @throws Exception
     *
     * @return Link
     */
    public function addLink(LinkableInterface $entity, Link $link) {
        $link->setEntity($entity);
        $this->em->persist($link);

        return $link;
    }

    public function setLinks(LinkableInterface $entity, $links) : void {
        foreach ($entity->getLinks() as $link) {
            $this->em->remove($link);
        }
        foreach ($links as $link) {
            $entity->addLink($link);
            $this->em->persist($link);
        }
    }

    public function linkToEntity($citation) {
        [$class, $id] = explode(':', $citation->getEntity());

        return $this->router->generate($this->routing[$class], ['id' => $id]);
    }

    public function getSubscribedEvents() {
        return [
            Events::postLoad,
            Events::preRemove,
        ];
    }

    public function postLoad(LifecycleEventArgs $args) : void {
        $entity = $args->getObject();
        if ( ! $entity instanceof LinkableInterface) {
            return;
        }
        $entity->setLinks($this->findLinks($entity));
    }

    public function preRemove(LifecycleEventArgs $args) : void {
        $entity = $args->getObject();
        if ( ! $entity instanceof LinkableInterface) {
            return;
        }
        foreach ($entity->getLinks() as $link) {
            $this->em->remove($link);
        }
    }
}
