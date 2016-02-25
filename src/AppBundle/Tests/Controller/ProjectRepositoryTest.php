<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Tests\Controller\WebTestCase;
use UserBundle\Entity\User;

class ProjectRepositoryTest extends WebTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $_em;

    public function setUp()
    {
        $kernel = static::createKernel();
        $kernel->boot();
        $this->_em = $kernel->getContainer()
            ->get('doctrine.orm.entity_manager');
    }

    /**
     * @param $userName
     * @dataProvider userProvider
     */
    public function testFindByUsers($userName)
    {
        $user = $this->_em->getRepository('UserBundle:User')->findOneByUsername($userName);
        $results = $this->_em->getRepository('AppBundle:Project')
            ->findByUsers($user);

        $this->assertEquals(count($results), 1);
    }

    public function userProvider()
    {
        return [
            ['admin'],
            ['manager'],
            ['operator']
        ];
    }
}
