<?php
namespace UserBundle\Entity\Mapping;

use AppBundle\Entity\Mapping\EnumAbstract;

class EnumTimezoneUser extends EnumAbstract
{
    const AMSTREDAM = 'Europe/Amsterdam';
    const ANDORRA = 'Europe/Andorra';
    const ATHENS = 'Europe/Athens';
    const BELFAST = 'Europe/Belfast';
    const BELGRADE = 'Europe/Belgrade';
    const BERLIN = 'Europe/Berlin';
    const BRATISLAVA = 'Europe/Bratislava';
    const KIEV = 'Europe/Kiev';
    const LONDON = 'Europe/London';

    const TYPE_NAME = 'enum_timezone_user';

    public function getName()
    {
        return self::TYPE_NAME;
    }

    public function getValues()
    {
        return [self::AMSTREDAM, self::ANDORRA, self::ATHENS, self::BELFAST, self::BELGRADE, self::BERLIN, self::BRATISLAVA, self::KIEV, self::LONDON];
    }
}
