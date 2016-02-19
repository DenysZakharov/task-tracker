<?php
namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Project;

class LoadProjectData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $project = new Project();
        $project
            ->setLabel('Project one')
            ->setSummary('Its first test project')
            ->setCode('project-one')
            ->addUser($this->getReference('user_admin'));
        $manager->persist($project);

        $projectTwo = new Project();
        $projectTwo
            ->setLabel('Project two')
            ->setSummary('Its second test project')
            ->setCode('project-two')
            ->addUser($this->getReference('user_manager'));
        $manager->persist($projectTwo);

        $projectThree = new Project();
        $projectThree
            ->setLabel('Project three')
            ->setSummary('Its third test project')
            ->setCode('project-three')
            ->addUser($this->getReference('user_operator'));
        $manager->persist($projectThree);

        $manager->flush();

        $this->addReference('project_one', $project);
        $this->addReference('project_two', $projectTwo);
        $this->addReference('project_three', $projectThree);
    }

    public function getOrder()
    {
        return 2;
    }
}