<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\Service;

use App\Tests\Entity\ContributorStandIn;
use Nines\UserBundle\DataFixtures\UserFixtures;
use Nines\UtilBundle\Tests\ControllerBaseCase;

class ContributionManagerTest extends ControllerBaseCase {
    protected function fixtures() : array {
        return [
            UserFixtures::class,
        ];
    }

    public function testAddContributorNoUser() : void {
        $s = new ContributorStandIn();
        $manager = self::$kernel->getContainer()->get('test.App\Service\ContributionManager');
        $manager->addContributor($s);
        $this->assertCount(0, $s->getContributions());
    }

    public function testAddContributorUser() : void {
        $this->login('user.user');
        $this->client->request('GET', '/');
        $s = new ContributorStandIn();
        $manager = self::$kernel->getContainer()->get('test.App\Service\ContributionManager');
        $manager->addContributor($s);
        $contributions = $s->getContributions();
        $this->assertCount(1, $contributions);
        $this->assertSame('Unprivileged user', $contributions[0]['name']);
        $this->assertSame(date('Y-m-d'), $contributions[0]['date']);
    }
}
