<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\DataFixtures;

use App\Entity\Location;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LocationFixtures extends Fixture {
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $em) : void {
        for ($i = 0; $i < 4; $i++) {
            $fixture = new Location();
            $fixture->setName('Name ' . $i);
            $fixture->setGeonameId($i);
            $fixture->setLatitude($i + 0.5);
            $fixture->setLongitude($i + 0.5);
            $fixture->setFclass(chr(65 + $i));
            $fixture->setFcode('Fcode ' . $i);
            $fixture->setCountry('A' . chr(70 + $i));
            $fixture->setDescription("<p>This is paragraph {$i}</p>");
            $fixture->addContribution(new DateTime('2020-01-01'), 'Test User');
            $em->persist($fixture);
            $this->setReference('location.' . $i, $fixture);
        }
        $em->flush();
    }
}
