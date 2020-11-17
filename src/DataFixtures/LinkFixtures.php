<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\DataFixtures;

use App\Entity\Link;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class LinkFixtures extends Fixture implements DependentFixtureInterface {
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $em) : void {
        for ($i = 0; $i < 4; $i++) {
            $fixture = new Link();
            $fixture->setEntity($this->getReference('person.' . $i));
            $fixture->setUrl('http://example.com/' . $i);
            $fixture->setText('Text ' . $i);

            $em->persist($fixture);
            $this->setReference('link.' . $i, $fixture);
        }
        $em->flush();
    }

    public function getDependencies() {
        return [
            PersonFixtures::class,
        ];
    }
}
