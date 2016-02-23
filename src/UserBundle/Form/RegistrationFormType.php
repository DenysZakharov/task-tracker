<?php

namespace UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use UserBundle\Entity\Mapping\EnumTimezoneUser;

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
            ->add('timezone', 'choice', [
                'choices' => [
                    EnumTimezoneUser::AMSTREDAM => 'timezone.europe.amsterdam',
                    EnumTimezoneUser::ANDORRA => 'timezone.europe.andorra',
                    EnumTimezoneUser::ATHENS => 'timezone.europe.athens',
                    EnumTimezoneUser::BELFAST => 'timezone.europe.belfast',
                    EnumTimezoneUser::BELGRADE => 'timezone.europe.belgrade',
                    EnumTimezoneUser::BERLIN => 'timezone.europe.berlin',
                    EnumTimezoneUser::KIEV => 'timezone.europe.kiev',
                    EnumTimezoneUser::LONDON => 'timezone.europe.london'
                ],
                'required' => true,
                'translation_domain' => 'UserBundle'
            ])
            ->add('plainPassword', 'repeated', [
                'type' => 'password',
                'first_options' => $attrPasswordFirst,
                'second_options' => $attrPasswordSecond,
                'invalid_message' => 'fos_user.password.mismatch'
            ]);
    }
}
