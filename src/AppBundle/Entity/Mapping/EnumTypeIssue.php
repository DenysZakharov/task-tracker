<?php
namespace AppBundle\Entity\Mapping;

class EnumTypeIssue extends EnumAbstract
{
    protected $name = 'enum_type_issue';
    protected $values = ['Bug', 'Subtask', 'Task', 'Story'];
}
