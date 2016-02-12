<?php
namespace AppBundle\Entity\Mapping;

class EnumPriorityIssue extends EnumAbstract
{
    protected $name = 'enum_priority_issue';
    protected $values = ['Trivial', 'Minor', 'Major', 'Critical', 'Blocker'];
}
