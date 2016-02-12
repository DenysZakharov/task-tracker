<?php
namespace AppBundle\Entity\Mapping;

class EnumResolutionIssue extends EnumAbstract
{
    protected $name = 'enum_resolution_issue';
    protected $values = ['Fixed', 'Won_t_fix', 'Cannot_reproduce', 'Done', 'Won_t_done'];
}
