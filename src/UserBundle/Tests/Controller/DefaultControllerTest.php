<?php

namespace UserBundle\Tests\Controller;

use AppBundle\Tests\Controller\WebTestCase;
use UserBundle\Entity\User;

class DefaultControllerTest extends WebTestCase
{
    public function testViewList()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'test'
        ));

        $crawler = $client->request('GET', '/user/list');
        $this->assertContains('All users', $crawler->html());
    }
}
