<?php
namespace AppBundle\Entity\Mapping;

class EnumTypeIssue extends EnumAbstract
{
    const BUG = 'bug';
    const SUBTASK = 'subtask';
    const TASK = 'task';
    const STORY = 'story';

    const TYPE_NAME = 'enum_type_issue';

    public function getName()
    {
        return self::TYPE_NAME;
    }

    public function getValues()
    {
        return [self::BUG, self::STORY, self::SUBTASK, self::TASK];
    }
}
