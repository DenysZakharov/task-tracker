<?php
namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use UserBundle\Entity\User;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface, FixtureInterface, ContainerAwareInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $userManager = $this->container->get('fos_user.user_manager');

        $admin = $userManager->createUser();
        $admin
            ->setUsername('admin')
            ->setEmail('admin@test.com')
            ->setPlainPassword('test')
            ->setFullName('Administartor')
            ->setEnabled(true)
            ->addRole(User::ROLE_ADMIN);
        $userManager->updateUser($admin);

        $manager = $userManager->createUser();
        $manager
            ->setUsername('manager')
            ->setEmail('manager@test.com')
            ->setPlainPassword('test')
            ->setFullName('Manager')
            ->setEnabled(true)
            ->addRole(User::ROLE_MANAGER);
        $userManager->updateUser($manager);

        $operator = $userManager->createUser();
        $operator
            ->setUsername('operator')
            ->setEmail('operator@test.com')
            ->setPlainPassword('test')
            ->setFullName('Operator')
            ->setEnabled(true)
            ->addRole(User::ROLE_OPERATOR);
        $userManager->updateUser($operator);

        $this->addReference('user_admin', $admin);
        $this->addReference('user_manager', $manager);
        $this->addReference('user_operator', $operator);
    }

    public function getOrder()
    {
        return 1;
    }
}