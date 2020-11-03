<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\Controller;

use App\DataFixtures\ReferenceFixtures;
use App\Repository\ReferenceRepository;
use Nines\UserBundle\DataFixtures\UserFixtures;
use Nines\UtilBundle\Tests\ControllerBaseCase;
use Symfony\Component\HttpFoundation\Response;

class ReferenceTest extends ControllerBaseCase {
    // Change this to HTTP_OK when the site is public.
    private const ANON_RESPONSE_CODE = Response::HTTP_FOUND;

    protected function fixtures() : array {
        return [
            ReferenceFixtures::class,
            UserFixtures::class,
        ];
    }

    /**
     * @group anon
     * @group index
     */
    public function testAnonIndex() : void {
        $crawler = $this->client->request('GET', '/reference/');
        $this->assertSame(self::ANON_RESPONSE_CODE, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @group user
     * @group index
     */
    public function testUserIndex() : void {
        $this->login('user.user');
        $crawler = $this->client->request('GET', '/reference/');
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @group admin
     * @group index
     */
    public function testAdminIndex() : void {
        $this->login('user.admin');
        $crawler = $this->client->request('GET', '/reference/');
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @group anon
     * @group show
     */
    public function testAnonShow() : void {
        $crawler = $this->client->request('GET', '/reference/1');
        $this->assertSame(self::ANON_RESPONSE_CODE, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @group user
     * @group show
     */
    public function testUserShow() : void {
        $this->login('user.user');
        $crawler = $this->client->request('GET', '/reference/1');
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @group admin
     * @group show
     */
    public function testAdminShow() : void {
        $this->login('user.admin');
        $crawler = $this->client->request('GET', '/reference/1');
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testAnonSearch() : void {
        $repo = $this->createMock(ReferenceRepository::class);
        $repo->method('searchQuery')->willReturn([$this->getReference('reference.1')]);
        $this->client->disableReboot();
        $this->client->getContainer()->set('test.' . ReferenceRepository::class, $repo);

        $crawler = $this->client->request('GET', '/reference/search');
        $this->assertSame(self::ANON_RESPONSE_CODE, $this->client->getResponse()->getStatusCode());
        if (self::ANON_RESPONSE_CODE === Response::HTTP_FOUND) {
            // If authentication is required stop here.
            return;
        }

        $form = $crawler->selectButton('btn-search')->form([
            'q' => 'reference',
        ]);

        $responseCrawler = $this->client->submit($form);
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
    }

    public function testUserSearch() : void {
        $repo = $this->createMock(ReferenceRepository::class);
        $repo->method('searchQuery')->willReturn([$this->getReference('reference.1')]);
        $this->client->disableReboot();
        $this->client->getContainer()->set('test.' . ReferenceRepository::class, $repo);

        $this->login('user.user');
        $crawler = $this->client->request('GET', '/reference/search');
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('btn-search')->form([
            'q' => 'reference',
        ]);

        $responseCrawler = $this->client->submit($form);
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
    }

    public function testAdminSearch() : void {
        $repo = $this->createMock(ReferenceRepository::class);
        $repo->method('searchQuery')->willReturn([$this->getReference('reference.1')]);
        $this->client->disableReboot();
        $this->client->getContainer()->set('test.' . ReferenceRepository::class, $repo);

        $this->login('user.admin');
        $crawler = $this->client->request('GET', '/reference/search');
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('btn-search')->form([
            'q' => 'reference',
        ]);

        $responseCrawler = $this->client->submit($form);
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
    }

}
