<?php

namespace UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
            ->add('roles', 'choice', [
                'choices' => [
                    'ROLE_ADMIN' => "role.admin",
                    'ROLE_MANAGER' => "role.manager",
                    'ROLE_OPERATOR' => "role.operator"
                ],
                'label' => 'Roles',
                'multiple' => true,
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
