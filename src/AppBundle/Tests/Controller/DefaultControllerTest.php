<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Tests\Controller\WebTestCase;


class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'test'
        ));

        $crawler = $client->request('GET', '/');
        self::assertContains('login', $crawler->html());
    }
}
