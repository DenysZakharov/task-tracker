<?php

namespace AppBundle\Tests\Unit;

use AppBundle\Form\ProjectFormType;

class ProjectTypeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ProjectType
     */
    protected $type;

    protected function setUp()
    {
        $this->type = new ProjectFormType();
    }

    protected function tearDown()
    {
        unset($this->type);
    }

    public function testFields()
    {
        $builder = $this->getMockBuilder('Symfony\Component\Form\FormBuilder')
            ->disableOriginalConstructor()
            ->getMock();

        $builder->expects($this->any())->method('add')->will($this->returnSelf());
        $builder->expects($this->exactly(4))->method('add');

        $this->type->buildForm($builder, []);
    }

    public function testSetDefaultOptions()
    {
        $resolver = $this->getMock('Symfony\Component\OptionsResolver\OptionsResolver');
        $resolver->expects($this->once())
            ->method('setDefaults')
            ->with($this->isType('array'));
        $this->type->setDefaultOptions($resolver);
    }

    public function testHasName()
    {
        $this->assertEquals('app_project', $this->type->getName());
    }
}
