<?php

namespace UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use UserBundle\Entity\User;
use UserBundle\Entity\Mapping\EnumTimezoneUser;

class UserType extends AbstractType
{
    public function getName()
    {
        return 'user_create';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => 'UserBundle\Entity\User']);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $attributeDefault = [
            'translation_domain' => 'FOSUserBundle'
        ];
        $builder
            ->add('email', 'email', $attributeDefault)
            ->add('username', 'text', $attributeDefault)
            ->add('fullName', 'text', $attributeDefault)
            ->add('plainPassword', 'repeated', [
                'type' => 'password',
                'options' => [
                    'translation_domain' => 'FOSUserBundle'
                ],
                'first_options' => [
                    'label' => 'form.password'
                ],
                'second_options' => [
                    'label' => 'form.password_confirmation'
                ],
                'invalid_message' => 'fos_user.password.mismatch'
            ])
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
            ->add('roles', 'choice', [
                'choices' => [
                    User::ROLE_ADMIN => 'role.admin',
                    User::ROLE_MANAGER => 'role.manager',
                    User::ROLE_OPERATOR => 'role.operator'
                ],
                'label' => 'Role',
                'mapped' => false,
                'required' => true,
                'translation_domain' => 'UserBundle'
            ])
            ->add('enabled', 'choice', [
                'choices' => [
                    '1' => 'Enable',
                    '0' => 'Disable'
                ],
                'label' => 'Enabled'
            ]);
    }
}
