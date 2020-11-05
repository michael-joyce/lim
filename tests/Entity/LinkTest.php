<?php


namespace App\Tests\Entity;


use App\Entity\Link;
use Exception;
use Nines\UtilBundle\Tests\BaseCase;

class LinkTest extends BaseCase {

    public function testToStringNoText() {
        $l = new Link();
        $l->setUrl('http://example.com/foo/bar');
        $this->assertEquals('<a href=\'http://example.com/foo/bar\'>example.com</a>', "$l");
    }

    public function testToString() {
        $l = new Link();
        $l->setUrl('http://example.com/foo/bar');
        $l->setText(' Here. ');
        $this->assertEquals('<a href=\'http://example.com/foo/bar\'>Here.</a>', "$l");
    }

    public function testSetEntityException() {
        $this->expectException(Exception::class);
        $l = new Link();
        $s = new LinkableStandIn();
        $l->setEntity($s);
    }

    public function testSetEntity() {
        $l = new Link();
        $s = new LinkableStandIn();
        $s->setId(1);
        $l->setEntity($s);
        $this->assertEquals(LinkableStandIn::class . ':1', $l->getEntity());
    }

}
