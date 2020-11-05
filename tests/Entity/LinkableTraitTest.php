<?php


namespace App\Tests\Entity;


use App\Entity\Link;
use Exception;
use Nines\UtilBundle\Tests\ServiceBaseCase;

class LinkableTraitTest extends ServiceBaseCase {

    public function testAddLink() {
        $s = new LinkableStandIn();
        $s->setId(1);
        $l = new Link();
        $l->setUrl('http://example.com');
        $s->addLink($l);
        $this->assertCount(1, $s->getLinks());
        $this->assertEquals($l->getEntity(), LinkableStandIn::class . ':' . 1);
    }

    public function testAddLinkException() {
        $this->expectException(Exception::class);
        $s = new LinkableStandIn();
        $l = new Link();
        $s->addLink($l);
    }

    public function testSetLinks() {
        $links = $this->getLinkData();
        $s = new LinkableStandIn();
        $s->setId(1);
        $s->setLinks($links);
        $this->assertCount(count($links), $s->getLinks());
        $this->assertEquals($links[0]->getEntity(), LinkableStandIn::class . ':' . 1);
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

}
