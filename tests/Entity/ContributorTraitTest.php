<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\Entity;

use DateTimeImmutable;
use Nines\UtilBundle\Tests\ServiceBaseCase;

class ContributorTraitTest extends ServiceBaseCase {
    public function testAddContribution() : void {
        $s = new ContributorStandIn();
        $s->addContribution(new DateTimeImmutable('2020-01-01'), 'MJ');
        $this->assertCount(1, $s->getContributions());
    }

    public function testAddMultipleContributions() : void {
        $s = new ContributorStandIn();
        $s->addContribution(new DateTimeImmutable('2020-01-01'), 'MJ');
        $s->addContribution(new DateTimeImmutable('2020-01-02'), 'AB');
        $s->addContribution(new DateTimeImmutable('2020-01-03'), 'BC');
        $this->assertCount(3, $s->getContributions());
    }

    public function testAddDuplicateContributions() : void {
        $s = new ContributorStandIn();
        $s->addContribution(new DateTimeImmutable('2020-01-01'), 'MJ');
        $s->addContribution(new DateTimeImmutable('2020-01-01'), 'BC');
        $s->addContribution(new DateTimeImmutable('2020-01-01'), 'MJ');
        $this->assertCount(2, $s->getContributions());
    }

    public function testGetContributionsSortedByDate() : void {
        $s = new ContributorStandIn();
        $s->addContribution(new DateTimeImmutable('2020-01-02'), 'AB');
        $s->addContribution(new DateTimeImmutable('2020-01-01'), 'MJ');
        $s->addContribution(new DateTimeImmutable('2020-01-03'), 'BC');
        $this->assertCount(3, $s->getContributions());
        $contributions = $s->getContributions();
        $this->assertSame('2020-01-03', $contributions[0]['date']);
        $this->assertSame('2020-01-02', $contributions[1]['date']);
        $this->assertSame('2020-01-01', $contributions[2]['date']);
    }

    public function testGetContributionsSortedByName() : void {
        $s = new ContributorStandIn();
        $s->addContribution(new DateTimeImmutable('2020-01-01'), 'AB');
        $s->addContribution(new DateTimeImmutable('2020-01-01'), 'MJ');
        $s->addContribution(new DateTimeImmutable('2020-01-01'), 'BC');
        $this->assertCount(3, $s->getContributions());
        $contributions = $s->getContributions();
        $this->assertSame('AB', $contributions[0]['name']);
        $this->assertSame('BC', $contributions[1]['name']);
        $this->assertSame('MJ', $contributions[2]['name']);
    }
}
