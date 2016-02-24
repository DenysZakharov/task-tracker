<?php

namespace UserBundle\Tests\Unit\Security;

use UserBundle\Entity\User;
use UserBundle\Security\UserVoter;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

class UserVoterTest extends \PHPUnit_Framework_TestCase
{
    protected $voter;

    protected $tokenInterface;

    protected $mockUser;

    protected function setUp()
    {
        $this->voter = new UserVoter();
        $this->tokenInterface = $this->getMock('Symfony\Component\Security\Core\Authentication\Token\TokenInterface');
        $this->mockUser = $this->getMock('UserBundle\Entity\User');
    }

    /**
     * @param $attribute
     * @param $isSupported
     * @dataProvider attributeProvider
     */
    public function testSupportsAttribute($attribute, $isSupported)
    {
        $this->assertEquals($isSupported, $this->voter->supportsAttribute($attribute));
    }

    public function attributeProvider()
    {
        return [
            [UserVoter::VIEW, true],
            [UserVoter::EDIT, true],
            [UserVoter::CREATE, true],
            ['DELETE', false],
        ];
    }

    /**
     * @param $class
     * @param $isSupported
     * @dataProvider classProvider
     */
    public function testSupportsClass($class, $isSupported)
    {
        $this->assertEquals($isSupported, $this->voter->supportsClass($class));
    }

    public function classProvider()
    {
        return [
            ['UserBundle\Entity\User', true],
            ['AppBundle\Entity\Project', false],
            ['Some\Unsupported\Class', false]
        ];
    }

    /**
     * @param $role
     * @param $expected
     * @param $attribute
     * @dataProvider voteProvider
     */
    public function testVote($role, $expected, $attribute)
    {
        $user = new User();
        $user->addRole($role);
        $this->tokenInterface->expects($this->any())->method('getUser')->will($this->returnValue($user));
        $this->assertEquals(
            $expected,
            $this->voter->vote(
                $this->tokenInterface,
                $this->mockUser,
                [$attribute]
            )
        );
    }

    public function voteProvider()
    {
        return [
            [User::ROLE_ADMIN, VoterInterface::ACCESS_ABSTAIN, UserVoter::VIEW],
            [User::ROLE_OPERATOR, VoterInterface::ACCESS_ABSTAIN, UserVoter::CREATE],
            [User::ROLE_MANAGER, VoterInterface::ACCESS_ABSTAIN, UserVoter::EDIT]
        ];
    }
}
