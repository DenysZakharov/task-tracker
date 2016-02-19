<?php

namespace AppBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class Builder implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
        $currentUser = $this->container->get('security.token_storage')->getToken()->getUser();
        $authChecker = $this->container->get('security.authorization_checker');
        $menu->addChild('Main page', [
            'route' => 'homepage',
            'label' => $this->container->get('translator')->trans('menu.main'),
        ]);
        $menu->addChild('User', [
            'route' => 'user_show',
            'label' => $this->container->get('translator')->trans('menu.user.info'),
            'routeParameters' => ['user' => $currentUser->getId()]
        ]);
        if (!false === $authChecker->isGranted(array('ROLE_ADMIN'))) {
            $menu['User']->addChild('Users list', [
                'label' => $this->container->get('translator')->trans('menu.user.list'),
                'route' => 'user_list'
            ]);
            $menu['User']->addChild('User new', [
                'label' => $this->container->get('translator')->trans('menu.user.new'),
                'route' => 'user_new'
            ]);
        }
        $menu->addChild('Project', [
            'route' => 'project',
            'label' => $this->container->get('translator')->trans('menu.project.list'),
        ]);
        if (!false === $authChecker->isGranted(array('ROLE_ADMIN', 'ROLE_MANAGER'))) {
            $menu['Project']->addChild('Project list', [
                'label' => $this->container->get('translator')->trans('menu.project.list'),
                'route' => 'project_list'
            ]);
            $menu['Project']->addChild('Project new', [
                'label' => $this->container->get('translator')->trans('menu.project.new'),
                'route' => 'project_new'
            ]);
        }
        $menu->addChild('Issue', [
            'route' => 'issue',
            'label' => $this->container->get('translator')->trans('menu.issue.list'),
        ]);
        if (!false === $authChecker->isGranted(array('ROLE_ADMIN', 'ROLE_MANAGER'))) {
            $menu['Issue']->addChild('Issue list', [
                'label' => $this->container->get('translator')->trans('menu.issue.list'),
                'route' => 'issue_list'
            ]);
            $menu['Issue']->addChild('Issue new', [
                'label' => $this->container->get('translator')->trans('menu.issue.new'),
                'route' => 'issue_new'
            ]);
        }
        return $menu;
    }
}