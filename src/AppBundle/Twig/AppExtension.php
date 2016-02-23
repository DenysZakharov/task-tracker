<?php

namespace AppBundle\Twig;

use AppBundle\Entity\Activity;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class AppExtension extends \Twig_Extension
{
    protected $security;

    public function __construct(TokenStorageInterface $security)
    {
        $this->security = $security;
    }

    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('formatDate', [$this, 'formatDateFilter']),
        ];
    }

    public function formatDateFilter($date)
    {
        if(!($date instanceof \DateTime)) {
            $date = new \DateTime($date);
        }
        $user = $this->security->getToken()->getUser();
        $date->setTimezone(new \DateTimeZone($user->getTimezone()));
        return $date->format('Y-m-d H:i:sP');
    }

    public function getName()
    {
        return 'app_extension';
    }
}