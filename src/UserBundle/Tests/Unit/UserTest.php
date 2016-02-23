<?php

namespace UserBundle\Tests\Unit;

use UserBundle\Entity\User;

class UserTest extends \PHPUnit_Framework_TestCase
{
    protected $user;

    protected function setUp()
    {
        $this->user = new User();
    }

    public function testToString()
    {
        $this->user->setFullName('Vasyliy Pupkin');
        $this->user->setUsername('Vasya');

        $this->assertEquals($this->user->getFullName(), 'Vasyliy Pupkin');
        $this->assertEquals($this->user->getUsername(), 'Vasya');
        $this->assertEquals($this->user->getUsername(), (string)$this->user);
    }

    public function testProject()
    {
        $this->assertCount(0, $this->user->getProject());

        $project = $this->getMock("AppBundle\\Entity\\Project");
        $this->user->addProject($project);
        $this->assertCount(1, $this->user->getProject());

        $this->user->removeProject($project);
        $this->assertCount(0, $this->user->getProject());
    }

    public function testIssues()
    {
        $this->assertCount(0, $this->user->getIssues());

        $issue = $this->getMock("AppBundle\\Entity\\Issue");
        $this->user->addIssue($issue);
        $this->assertCount(1, $this->user->getIssues());

        $this->user->removeIssue($issue);
        $this->assertCount(0, $this->user->getIssues());
    }
}
