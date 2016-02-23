<?php

namespace UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

use UserBundle\Entity\Mapping\EnumTimezoneUser;

class ProfileFormType extends AbstractType
{
    public function getName()
    {
        return 'profile';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username', 'text', ['label' => 'form.username', 'translation_domain' => 'FOSUserBundle'])
            ->add('email', 'email', ['label' => 'form.email', 'translation_domain' => 'FOSUserBundle'])
            ->add('current_password', 'password', [
                'label' => 'form.current_password',
                'translation_domain' => 'FOSUserBundle',
                'mapped' => false,
                'constraints' => new UserPassword()
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
            ->add('fullName', 'text', [
                'translation_domain' => 'FOSUserBundle'
            ]);
    }

    public function getParent()
    {
        return 'fos_user_profile';
    }
}
