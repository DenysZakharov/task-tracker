<?php

namespace AppBundle\Tests\Unit\Security;

use UserBundle\Entity\User;
use AppBundle\Entity\Project;
use AppBundle\Security\ProjectVoter;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

class ProjectVoterTest extends \PHPUnit_Framework_TestCase
{
    protected $voter;

    protected $tokenInterface;

    protected $mockProject;

    protected function setUp()
    {
        $this->voter = new ProjectVoter();
        $this->tokenInterface = $this->getMock('Symfony\Component\Security\Core\Authentication\Token\TokenInterface');
        $this->mockProject = new Project();
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
            [ProjectVoter::VIEW, true],
            [ProjectVoter::EDIT, true],
            [ProjectVoter::CREATE, true],
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
            ['AppBundle\Entity\Issue', false],
            ['AppBundle\Entity\Project', true],
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
                $this->mockProject,
                [$attribute]
            )
        );
    }

    public function voteProvider()
    {
        return [
            [User::ROLE_ADMIN, VoterInterface::ACCESS_GRANTED, ProjectVoter::VIEW],
            [User::ROLE_OPERATOR, VoterInterface::ACCESS_ABSTAIN, ProjectVoter::CREATE],
            [User::ROLE_MANAGER, VoterInterface::ACCESS_GRANTED, ProjectVoter::EDIT]
        ];
    }
}
