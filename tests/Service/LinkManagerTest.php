<?php


namespace App\Tests\Service;


use App\DataFixtures\LinkFixtures;
use App\DataFixtures\PersonFixtures;
use App\Entity\Link;
use App\Entity\LinkableInterface;
use App\Entity\LinkableTrait;
use App\Entity\Person;
use App\Repository\LinkRepository;
use App\Service\LinkManager;
use App\Tests\Entity\LinkableStandIn;
use Doctrine\ORM\EntityManagerInterface;
use Nines\UtilBundle\Entity\AbstractEntity;
use Nines\UtilBundle\Tests\ControllerBaseCase;
use Nines\UtilBundle\Tests\ServiceBaseCase;
use ReflectionClass;

class LinkManagerTest extends ControllerBaseCase {

    public function testConfig() {
        $manager = self::$kernel->getContainer()->get('test.App\Service\LinkManager');
        $this->assertInstanceOf(LinkManager::class, $manager);
    }

    public function testAcceptsLinks() {
        $manager = self::$kernel->getContainer()->get('test.App\Service\LinkManager');
        $s = new LinkableStandIn();
        $this->assertTrue($manager->acceptsLinks($s));
    }

    public function testFindEntity() {
        $link = $this->getReference('link.1');
        $manager = self::$kernel->getContainer()->get('test.App\Service\LinkManager');
        $found = $manager->findEntity($link);
        $this->assertNotNull($found);
        $this->assertInstanceOf(Person::class, $found);
        $this->assertEquals('FullName 1', $found->getFullName());
    }

    public function testFindEntityUnknownClass() {
        $s = new LinkableStandIn();
        $s->setId(1);
        $link = new Link();
        $link->setEntity($s);

        $manager = self::$kernel->getContainer()->get('test.App\Service\LinkManager');
        $found = $manager->findEntity($link);
        $this->assertNull($found);
    }

    public function testFindEntityUnknownId() {
        $link = new Link();
        $reflection = new ReflectionClass(Link::class);
        $property = $reflection->getProperty('entity');
        $property->setAccessible(true);
        $property->setValue($link, Person::class . ':1234');

        $manager = self::$kernel->getContainer()->get('test.App\Service\LinkManager');
        $found = $manager->findEntity($link);
        $this->assertNull($found);
    }

    public function testEntityType() {
        $link = $this->getReference('link.1');
        $manager = self::$kernel->getContainer()->get('test.App\Service\LinkManager');
        $this->assertEquals('Person', $manager->entityType($link));
    }

    public function testEntityTypeUnknownClass() {
        $s = new LinkableStandIn();
        $s->setId(1);
        $link = new Link();
        $link->setEntity($s);

        $manager = self::$kernel->getContainer()->get('test.App\Service\LinkManager');
        $this->assertNull($manager->entityType($link));
    }

    public function testEntityTypeUnknownId() {
        $link = new Link();
        $reflection = new ReflectionClass(Link::class);
        $property = $reflection->getProperty('entity');
        $property->setAccessible(true);
        $property->setValue($link, Person::class . ':1234');

        $manager = self::$kernel->getContainer()->get('test.App\Service\LinkManager');
        $found = $manager->entityType($link);
        $this->assertNull($found);
    }

    public function testFindLinks() {
        $person = $this->getReference('person.0');

        $manager = self::$kernel->getContainer()->get('test.App\Service\LinkManager');
        $found = $manager->findLinks($person);
        $this->assertCount(1, $found);
    }

    public function testSetLinks() {
        $links = $this->getLinkData();
        $person = $this->getReference('person.0');
        $manager = self::$kernel->getContainer()->get('test.App\Service\LinkManager');
        $manager->setLinks($person, $links);
        $this->assertCount(2, $person->getLinks());
    }

    public function testLinkToEntity() {
        $link = $this->getReference('link.1');
        $manager = self::$kernel->getContainer()->get('test.App\Service\LinkManager');
        $this->assertEquals('/lim/person/2', $manager->linkToEntity($link));
    }

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

}
