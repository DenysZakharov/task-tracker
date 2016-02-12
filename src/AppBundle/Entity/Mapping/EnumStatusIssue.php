<?php
namespace AppBundle\Entity\Mapping;

class EnumStatusIssue extends EnumAbstract
{
    protected $name = 'enum_status_issue';
    protected $values = ['Open', 'In progress', 'Closed'];
}
