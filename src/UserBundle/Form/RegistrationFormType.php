<?php

namespace UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;



class RegistrationFormType extends AbstractType
{
    public function getParent()
    {
        return 'fos_user_registration';
    }

    public function getName()
    {
        return 'registration';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $attributeDefault = array(
            'label_attr' => array('class' => 'col-sm-4 control-label'),
            'translation_domain' => 'FOSUserBundle',
            'attr'=>array('class'=> 'form-control')
        );

        $attributeEmail = array_merge($attributeDefault, array(
            'label' => 'form.email'
        ));

        $attributeUserName = array_merge($attributeDefault, array(
            'label' => 'form.username'
        ));

        $attrPasswordFirst = array_merge($attributeDefault, array(
            'label' => 'form.password'
        ));

        $attrPasswordSecond = array_merge($attributeDefault, array(
            'label' => 'form.password_confirmation'
        ));

        $attributeFullName = array_merge($attributeDefault, array(
            'translation_domain' => 'UserBundle',
        ));

        $builder
            ->add('email', 'email', $attributeEmail)
            ->add('fullName', null, $attributeFullName)
            ->add('username', null, $attributeUserName)
            ->add('plainPassword', 'repeated', array(
                'type' => 'password',
                'first_options' => $attrPasswordFirst,
                'second_options' => $attrPasswordSecond,
                'invalid_message' => 'fos_user.password.mismatch'
            ));
    }
}
