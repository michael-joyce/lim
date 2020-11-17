<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\Entity;

use App\Entity\Link;
use Exception;
use Nines\UtilBundle\Tests\BaseCase;

class LinkTest extends BaseCase {
    public function testToStringNoText() : void {
        $l = new Link();
        $l->setUrl('http://example.com/foo/bar');
        $this->assertSame('<a href=\'http://example.com/foo/bar\'>example.com</a>', "{$l}");
    }

    public function testToString() : void {
        $l = new Link();
        $l->setUrl('http://example.com/foo/bar');
        $l->setText(' Here. ');
        $this->assertSame('<a href=\'http://example.com/foo/bar\'>Here.</a>', "{$l}");
    }

    public function testSetEntityException() : void {
        $this->expectException(Exception::class);
        $l = new Link();
        $s = new LinkableStandIn();
        $l->setEntity($s);
    }

    public function testSetEntity() : void {
        $l = new Link();
        $s = new LinkableStandIn();
        $s->setId(1);
        $l->setEntity($s);
        $this->assertSame(LinkableStandIn::class . ':1', $l->getEntity());
    }
}
