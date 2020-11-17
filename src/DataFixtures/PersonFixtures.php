<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\DataFixtures;

use App\Entity\Person;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PersonFixtures extends Fixture implements DependentFixtureInterface {
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $em) : void {
        for ($i = 0; $i < 4; $i++) {
            $fixture = new Person();
            $fixture->setFullName('FullName ' . $i);
            $fixture->setSortableName('SortableName ' . $i);
            $fixture->setGender((0 === $i % 2) ? Person::FEMALE : Person::MALE);
            $fixture->setBiography("<p>This is paragraph {$i}</p>");
            $fixture->addOccupation('Occupation ' . $i, null);
            $fixture->addTitle('Title ' . $i, null);
            $fixture->addContribution(new DateTimeImmutable('2020-01-01'), 'Test User');
            $fixture->setBirthyear($this->getReference('circadate.' . $i));
            $fixture->setDeathyear($this->getReference('circadate.' . $i));
            $fixture->setBirthplace($this->getReference('location.' . $i));
            $fixture->setDeathplace($this->getReference('location.' . $i));
            $em->persist($fixture);
            $this->setReference('person.' . $i, $fixture);
        }
        $em->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getDependencies() {
        return [
            CircaDateFixtures::class,
            LocationFixtures::class,
        ];
    }
}
