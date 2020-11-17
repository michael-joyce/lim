<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class to build some menus for navigation.
 */
class Builder implements ContainerAwareInterface {
    use ContainerAwareTrait;

    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * @var AuthorizationCheckerInterface
     */
    private $authChecker;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    public function __construct(FactoryInterface $factory, AuthorizationCheckerInterface $authChecker, TokenStorageInterface $tokenStorage) {
        $this->factory = $factory;
        $this->authChecker = $authChecker;
        $this->tokenStorage = $tokenStorage;
    }

    private function hasRole($role) {
        if ( ! $this->tokenStorage->getToken()) {
            return false;
        }

        return $this->authChecker->isGranted($role);
    }

    /**
     * Build a menu for navigation.
     */
    public function mainMenu(array $options) : ItemInterface {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttributes([
            'class' => 'nav navbar-nav',
        ]);

        $browse = $menu->addChild('Browse', [
            'uri' => '#',
            'label' => 'Browse',
        ]);
        $browse->setAttribute('dropdown', true);
        $browse->setLinkAttribute('class', 'dropdown-toggle');
        $browse->setLinkAttribute('data-toggle', 'dropdown');
        $browse->setChildrenAttribute('class', 'dropdown-menu');

        $browse->addChild('People', [
            'route' => 'person_index',
        ]);

        if ($this->hasRole('ROLE_CONTENT_ADMIN')) {
            $divider = $browse->addChild('divider_content', [
                'label' => '',
            ]);
            $divider->setAttributes([
                'role' => 'separator',
                'class' => 'divider',
            ]);
            $browse->addChild('Locations', [
                'route' => 'location_index',
            ]);

            $browse->addChild('Reference Roles', [
                'route' => 'reference_role_index',
            ]);
            $browse->addChild('References', [
                'route' => 'reference_index',
            ]);
        }

        if ($this->hasRole('ROLE_ADMIN')) {
            $divider = $browse->addChild('divider_admin', [
                'label' => '',
            ]);
            $divider->setAttributes([
                'role' => 'separator',
                'class' => 'divider',
            ]);
            $browse->addChild('Dates', [
                'route' => 'circa_date_index',
            ]);
            $browse->addChild('Links', [
                'route' => 'link_index',
            ]);
        }

        return $menu;
    }

    /**
     * Build a menu for navigation.
     */
    public function footerMenu(array $options) : ItemInterface {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttributes([
            'class' => 'nav navbar-nav',
        ]);

        $menu->addChild('Home', [
            'route' => 'homepage',
        ]);
        $menu->addChild('Privacy', [
            'route' => 'privacy',
        ]);
        $menu->addChild('GitHub', [
            'uri' => 'https://github.com/sfu-dhil/lim',
        ]);

        return $menu;
    }
}
