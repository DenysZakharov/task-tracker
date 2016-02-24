<?php

namespace UserBundle\Tests\Unit;

use Symfony\Component\Translation\TranslatorInterface;

use UserBundle\Form\UserType;

class UserTypeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var UserType
     */
    protected $type;

    protected function setUp()
    {
        $this->type = new UserType();
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
        $builder->expects($this->exactly(7))->method('add')->will($this->returnSelf());

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
        $this->assertEquals('user_create', $this->type->getName());
    }
}
