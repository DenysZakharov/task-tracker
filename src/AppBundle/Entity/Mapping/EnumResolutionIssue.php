<?php
namespace AppBundle\Entity\Mapping;

class EnumResolutionIssue extends EnumAbstract
{
    const FIXED = 'fixed';
    const WONTFIX = 'wont_fix';
    const CANNOT_REPRODUCE = 'cannot_reproduce';
    const DONE = 'done';
    const WONTDONE = 'wont_done';

    const TYPE_NAME = 'enum_resolution_issue';

    public function getName()
    {
        return self::TYPE_NAME;
    }

    public function getValues()
    {
        return [self::FIXED, self::WONTDONE, self::DONE, self::WONTFIX, self::CANNOT_REPRODUCE, null];
    }
}
