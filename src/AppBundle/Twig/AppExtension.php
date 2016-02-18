<?php

namespace AppBundle\Twig;

use AppBundle\Entity\Activity;

class AppExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('formatDate', [$this, 'formatDateFilter']),
        ];
    }


    public function formatDateFilter(\DateTime $date)
    {
        return $date->format('Y-m-d H:i:sP');
    }

    public function getName()
    {
        return 'app_extension';
    }
}