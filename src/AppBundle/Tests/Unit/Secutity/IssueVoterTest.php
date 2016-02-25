<?php

namespace AppBundle\Tests\Unit\Security;

use UserBundle\Entity\User;
use AppBundle\Entity\Issue;
use AppBundle\Security\IssueVoter;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

class IssueVoterTest extends \PHPUnit_Framework_TestCase
{
    protected $voter;

    protected $tokenInterface;

    protected $mockIssue;

    protected function setUp()
    {
        $this->voter = new IssueVoter();
        $this->tokenInterface = $this->getMock('Symfony\Component\Security\Core\Authentication\Token\TokenInterface');
        $this->mockIssue = new Issue();
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
            [IssueVoter::VIEW, true],
            [IssueVoter::EDIT, true],
            [IssueVoter::CREATE, true],
            [IssueVoter::ADD_SUB_TASK, true],
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
            ['AppBundle\Entity\Issue', true],
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
                $this->mockIssue,
                [$attribute]
            )
        );
    }

    public function voteProvider()
    {
        return [
            [User::ROLE_ADMIN, VoterInterface::ACCESS_GRANTED, IssueVoter::VIEW],
            [User::ROLE_OPERATOR, VoterInterface::ACCESS_ABSTAIN, IssueVoter::CREATE],
            [User::ROLE_OPERATOR, VoterInterface::ACCESS_ABSTAIN, IssueVoter::ADD_SUB_TASK],
            [User::ROLE_MANAGER, VoterInterface::ACCESS_GRANTED, IssueVoter::EDIT]
        ];
    }
}
