<?php

namespace UserBundle\Tests\Controller;

use Symfony\Component\HttpFoundation\Response;
use AppBundle\Tests\Controller\WebTestCase;

class OperatorSecurityTest extends WebTestCase
{
    /**
     * @dataProvider urlProvider
     */
    public function testPageIsSuccessful($url)
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'operator',
            'PHP_AUTH_PW' => 'test'
        ]);

        $client->request('GET', $url);
        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    public function urlProvider()
    {
        return [
            ['/'],
            ['/issue/new'],
            ['/issue/task-three/edit'],
            ['/issue/task-three'],
            ['/project/']
        ];
    }
}
