<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseWebTestCase;

class WebTestCase extends BaseWebTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    public function setUp()
    {
        $client = self::createClient();
        $container = $client->getKernel()->getContainer();
        $em = $container->get('doctrine')->getManager();

        // Purge tables
        $purger = new \Doctrine\Common\DataFixtures\Purger\ORMPurger($em);
        $executor = new \Doctrine\Common\DataFixtures\Executor\ORMExecutor($em, $purger);
        $executor->purge();

        // Load fixtures
        $loader = new \Doctrine\Common\DataFixtures\Loader;
        $users = new \AppBundle\DataFixtures\ORM\LoadUserData();
        $users->setContainer($container);
        $loader->addFixture($users);

        $projects = new \AppBundle\DataFixtures\ORM\LoadProjectData();
        $projects->setContainer($container);
        $loader->addFixture($projects);

        $issues = new \AppBundle\DataFixtures\ORM\LoadIssueData();
        $issues->setContainer($container);
        $loader->addFixture($issues);

        $activity = new \AppBundle\DataFixtures\ORM\LoadActivityData();
        $activity->setContainer($container);
        $loader->addFixture($activity);

        $executor->execute($loader->getFixtures());
    }
}
