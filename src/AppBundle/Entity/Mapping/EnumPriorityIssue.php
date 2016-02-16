<?php
namespace AppBundle\Entity\Mapping;

class EnumPriorityIssue extends EnumAbstract
{
    const TRIVIAL = 'trivial';
    const MINOR = 'minor';
    const MAJOR = 'major';
    const CRITICAL = 'critical';
    const BLOCKER = 'blocker';

    const TYPE_NAME = 'enum_priority_issue';

    public function getName()
    {
        return self::TYPE_NAME;
    }

    public function getValues()
    {
        return [self::TRIVIAL, self::MINOR, self::MAJOR, self::CRITICAL, self::BLOCKER];
    }
}
