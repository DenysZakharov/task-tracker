<?php

namespace UserBundle\Tests\Controller;

use Symfony\Component\HttpFoundation\Response;
use AppBundle\Tests\Controller\WebTestCase;


class ManagerSecurityTest extends WebTestCase
{

    public function testCreateIssue()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'manager',
            'PHP_AUTH_PW' => 'test'
        ));

        $client->request('GET', '/issue/new');
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    public function testViewIssue()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'manager',
            'PHP_AUTH_PW' => 'test'
        ));

        $client->request('GET', '/issue/task-one');
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    public function testEditIssue()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'manager',
            'PHP_AUTH_PW' => 'test'
        ));

        $client->request('GET', '/issue/task-one/edit');
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }
}
