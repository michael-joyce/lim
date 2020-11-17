<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\Service;

use App\DataFixtures\LinkFixtures;
use App\DataFixtures\PersonFixtures;
use App\Entity\Link;
use App\Entity\Person;
use App\Service\LinkManager;
use App\Tests\Entity\LinkableStandIn;
use Nines\UtilBundle\Tests\ControllerBaseCase;
use ReflectionClass;

class LinkManagerTest extends ControllerBaseCase {
    protected function getLinkData() {
        $data = [
            ['http://example.com/link', 'Example'],
            ['http://example.com/other', null],
        ];
        $links = [];

        foreach ($data as $d) {
            $l = new Link();
            $l->setUrl($d[0]);
            $l->setText($d[1]);
            $links[] = $l;
        }

        return $links;
    }

    protected function fixtures() : array {
        return [
            PersonFixtures::class,
            LinkFixtures::class,
        ];
    }

    public function testConfig() : void {
        $manager = self::$kernel->getContainer()->get('test.App\Service\LinkManager');
        $this->assertInstanceOf(LinkManager::class, $manager);
    }

    public function testAcceptsLinks() : void {
        $manager = self::$kernel->getContainer()->get('test.App\Service\LinkManager');
        $s = new LinkableStandIn();
        $this->assertTrue($manager->acceptsLinks($s));
    }

    public function testFindEntity() : void {
        $link = $this->getReference('link.1');
        $manager = self::$kernel->getContainer()->get('test.App\Service\LinkManager');
        $found = $manager->findEntity($link);
        $this->assertNotNull($found);
        $this->assertInstanceOf(Person::class, $found);
        $this->assertSame('FullName 1', $found->getFullName());
    }

    public function testFindEntityUnknownClass() : void {
        $s = new LinkableStandIn();
        $s->setId(1);
        $link = new Link();
        $link->setEntity($s);

        $manager = self::$kernel->getContainer()->get('test.App\Service\LinkManager');
        $found = $manager->findEntity($link);
        $this->assertNull($found);
    }

    public function testFindEntityUnknownId() : void {
        $link = new Link();
        $reflection = new ReflectionClass(Link::class);
        $property = $reflection->getProperty('entity');
        $property->setAccessible(true);
        $property->setValue($link, Person::class . ':1234');

        $manager = self::$kernel->getContainer()->get('test.App\Service\LinkManager');
        $found = $manager->findEntity($link);
        $this->assertNull($found);
    }

    public function testEntityType() : void {
        $link = $this->getReference('link.1');
        $manager = self::$kernel->getContainer()->get('test.App\Service\LinkManager');
        $this->assertSame('Person', $manager->entityType($link));
    }

    public function testEntityTypeUnknownClass() : void {
        $s = new LinkableStandIn();
        $s->setId(1);
        $link = new Link();
        $link->setEntity($s);

        $manager = self::$kernel->getContainer()->get('test.App\Service\LinkManager');
        $this->assertNull($manager->entityType($link));
    }

    public function testEntityTypeUnknownId() : void {
        $link = new Link();
        $reflection = new ReflectionClass(Link::class);
        $property = $reflection->getProperty('entity');
        $property->setAccessible(true);
        $property->setValue($link, Person::class . ':1234');

        $manager = self::$kernel->getContainer()->get('test.App\Service\LinkManager');
        $found = $manager->entityType($link);
        $this->assertNull($found);
    }

    public function testFindLinks() : void {
        $person = $this->getReference('person.0');

        $manager = self::$kernel->getContainer()->get('test.App\Service\LinkManager');
        $found = $manager->findLinks($person);
        $this->assertCount(1, $found);
    }

    public function testSetLinks() : void {
        $links = $this->getLinkData();
        $person = $this->getReference('person.0');
        $manager = self::$kernel->getContainer()->get('test.App\Service\LinkManager');
        $manager->setLinks($person, $links);
        $this->assertCount(2, $person->getLinks());
    }

    public function testLinkToEntity() : void {
        $link = $this->getReference('link.1');
        $manager = self::$kernel->getContainer()->get('test.App\Service\LinkManager');
        $this->assertSame('/lim/person/2', $manager->linkToEntity($link));
    }
}
