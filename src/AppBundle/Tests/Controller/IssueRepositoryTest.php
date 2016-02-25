<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Tests\Controller\WebTestCase;
use UserBundle\Entity\User;

class IssueRepositoryTest extends WebTestCase
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
    public function testFindByUser($userName)
    {
        $user = $this->_em->getRepository('UserBundle:User')->findOneByUsername($userName);
        $results = $this->_em->getRepository('AppBundle:Issue')
            ->findByUser($user);

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

    /**
     * @param $userName
     * @param $count
     * @dataProvider collaboratorProvider
     */
    public function testFindByCollaborator($userName, $count)
    {
        $user = $this->_em->getRepository('UserBundle:User')->findOneByUsername($userName);
        $results = $this->_em->getRepository('AppBundle:Issue')
            ->findByCollaborator($user);

        $this->assertEquals(count($results), $count);
    }

    public function collaboratorProvider()
    {
        return [
            ['admin', 3],
            ['manager', 1],
            ['operator', 1]
        ];
    }
}
