<?php
namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use AppBundle\Entity\Activity;

class LoadActivityData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $activity = new Activity();
        $activity
            ->setUser($this->getReference('user_admin'))
            ->setIssue($this->getReference('issue_one'))
            ->setProject($this->getReference('project_one'))
            ->setType(Activity::CREATE_ISSUE);
        $manager->persist($activity);

        $activityTwo = new Activity();
        $activityTwo
            ->setUser($this->getReference('user_manager'))
            ->setIssue($this->getReference('issue_two'))
            ->setProject($this->getReference('project_two'))
            ->setType(Activity::CREATE_ISSUE);
        $manager->persist($activityTwo);

        $activityThree = new Activity();
        $activityThree
            ->setUser($this->getReference('user_operator'))
            ->setIssue($this->getReference('issue_three'))
            ->setProject($this->getReference('project_three'))
            ->setType(Activity::CREATE_ISSUE);
        $manager->persist($activityThree);

        $manager->flush();
    }

    public function getOrder()
    {
        return 4;
    }
}