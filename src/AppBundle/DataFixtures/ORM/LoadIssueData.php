<?php
namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use AppBundle\Entity\Issue;
use AppBundle\Entity\Mapping\EnumPriorityIssue;
use AppBundle\Entity\Mapping\EnumTypeIssue;
use AppBundle\Entity\Mapping\EnumStatusIssue;

class LoadIssueData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $admin = $this->getReference('user_admin');
        $userManager = $this->getReference('user_manager');
        $operator = $this->getReference('user_operator');

        $issue = new Issue();
        $issue
            ->setReporter($admin)
            ->setAssignee($admin)
            ->setProject($this->getReference('project_one'))
            ->setSummary('First task for admin')
            ->setCode('task-one')
            ->setDescription('Some test describe words for first task')
            ->setType(EnumTypeIssue::TASK)
            ->setPriority(EnumPriorityIssue::MINOR)
            ->setStatus(EnumStatusIssue::OPEN);
        $manager->persist($issue);

        $issueTwo = new Issue();
        $issueTwo
            ->setReporter($admin)
            ->setAssignee($userManager)
            ->setProject($this->getReference('project_two'))
            ->setSummary('Second task for admin')
            ->setCode('task-two')
            ->setDescription('Some test describe words for second task')
            ->setType(EnumTypeIssue::STORY)
            ->setPriority(EnumPriorityIssue::CRITICAL)
            ->setStatus(EnumStatusIssue::OPEN);
        $manager->persist($issueTwo);

        $issueThree = new Issue();
        $issueThree
            ->setReporter($admin)
            ->setAssignee($operator)
            ->setProject($this->getReference('project_three'))
            ->setSummary('Third task for admin')
            ->setCode('task-three')
            ->setDescription('Some test describe words for task number three')
            ->setType(EnumTypeIssue::BUG)
            ->setPriority(EnumPriorityIssue::MAJOR)
            ->setStatus(EnumStatusIssue::OPEN);
        $manager->persist($issueThree);

        $manager->flush();

        $this->addReference('issue_one', $issue);
        $this->addReference('issue_two', $issueTwo);
        $this->addReference('issue_three', $issueThree);
    }

    public function getOrder()
    {
        return 3;
    }
}