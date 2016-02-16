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
        $attributeDefault = [
            'translation_domain' => 'FOSUserBundle'
        ];

        $attributeEmail = array_merge($attributeDefault, ['label' => 'form.email']);
        $attributeUserName = array_merge($attributeDefault, ['label' => 'form.username']);
        $attrPasswordFirst = array_merge($attributeDefault, ['label' => 'form.password']);
        $attrPasswordSecond = array_merge($attributeDefault, ['label' => 'form.password_confirmation']);
        $attributeFullName = array_merge($attributeDefault, ['translation_domain' => 'UserBundle']);

        $builder
            ->add('email', 'email', $attributeEmail)
            ->add('fullName', 'text', $attributeFullName)
            ->add('username', 'text', $attributeUserName)
            ->add('plainPassword', 'repeated', [
                'type' => 'password',
                'first_options' => $attrPasswordFirst,
                'second_options' => $attrPasswordSecond,
                'invalid_message' => 'fos_user.password.mismatch'
            ]);
    }
}
