<?php
namespace AppBundle\Entity\Mapping;

class EnumStatusIssue extends EnumAbstract
{
    const OPEN = 'open';
    const INPROGRESS = 'in progress';
    const CLOSED = 'closed';

    const TYPE_NAME = 'enum_status_issue';

    public function getName()
    {
        return self::TYPE_NAME;
    }

    public function getValues()
    {
        return [self::OPEN, self::INPROGRESS, self::CLOSED];
    }
}
