<?php

namespace UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use UserBundle\Form\DataTransformer\StringToArrayTransformer;

class UserType extends AbstractType
{
    public function getName()
    {
        return 'user_create';
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $attributeDefault = $this->getDefaultAttributes();

        $builder
            ->add('email', 'email', $attributeDefault)
            ->add('username', null, $attributeDefault)
            ->add('fullName', null, $attributeDefault)
            ->add($this->getPlainPassword($builder))
            ->add($this->getRoles($builder))
            ->add($this->getEnabled($builder));

        if (!$builder->getData()->getId()) {
            $builder->add('save', 'submit', array('label' => 'user.form.create'));
        } else {
            $builder->add('save', 'submit', array('label' => 'user.form.update'));
        }
    }

    protected function getDefaultAttributes()
    {
        return array(
            'label_attr' => array('class' => 'col-sm-4 control-label'),
            'translation_domain' => 'FOSUserBundle',
            'attr'=>array('class'=> 'form-control')
        );
    }


    protected function getEnabled(FormBuilderInterface $builder)
    {
        $attrEnabled = array_merge(
            $this->getDefaultAttributes(),
            array(
                'choices' => array(
                    '1' => 'Enable',
                    '0' => 'Disable'
                ),
                'label' => 'Enabled'
            )
        );

        return $builder->create('enabled', 'choice', $attrEnabled);
    }

    protected function getPlainPassword(FormBuilderInterface $builder)
    {
        $attrPasswordFirst = array_merge(
            $this->getDefaultAttributes(),
            array(
                'label' => 'form.password'
            )
        );

        $attrPasswordSecond = array_merge(
            $this->getDefaultAttributes(),
            array(
                'label' => 'form.password_confirmation'
            )
        );

        $attributes = array(
            'type' => 'password',
            'options' => array('translation_domain' => 'FOSUserBundle'),
            'first_options' => $attrPasswordFirst,
            'second_options' => $attrPasswordSecond,
            'invalid_message' => 'fos_user.password.mismatch'
        );

        if ($builder->getData()->getid()) {
            $attributes ['required'] = false;
        }

        return $builder->create('plainPassword', 'repeated', $attributes);
    }

    protected function getRoles(FormBuilderInterface $builder)
    {
        $transformer = new StringToArrayTransformer();

        $attrRole = array_merge(
            $this->getDefaultAttributes(),
            array(
                'choices' => array(
                    'ROLE_ADMIN' => "Admin",
                    'ROLE_MANAGER' => "Manager",
                    'ROLE_OPERATOR' => "Operator"
                ),
                'label' => 'Roles',
                'expanded' => false,
                'multiple' => false,
                'mapped' => true
            )
        );

        return $builder->create('roles', 'choice', $attrRole)->addModelTransformer($transformer);
    }
}
