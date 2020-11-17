<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\DataFixtures;

use App\Entity\Reference;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ReferenceFixtures extends Fixture implements DependentFixtureInterface {
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $em) : void {
        for ($i = 0; $i < 4; $i++) {
            $fixture = new Reference();
            $fixture->setEntity($this->getReference('person.' . $i));
            $fixture->setCitation("<p>This is paragraph {$i}</p>");
            $fixture->setDescription("<p>This is paragraph {$i}</p>");
            $fixture->setReferencerole($this->getReference('referencerole.' . $i));
            $em->persist($fixture);
            $this->setReference('reference.' . $i, $fixture);
        }
        $em->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getDependencies() {
        return [
            ReferenceRoleFixtures::class,
            PersonFixtures::class,
        ];
    }
}
