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
            'label_attr' => ['class' => 'col-sm-4 control-label'],
            'translation_domain' => 'FOSUserBundle',
            'attr' => ['class' => 'form-control']
        ];
        $builder
            ->add('email', 'email', $attributeDefault)
            ->add('username', null, $attributeDefault)
            ->add('fullName', null, $attributeDefault)
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
                    'ROLE_ADMIN' => "Admin",
                    'ROLE_MANAGER' => "Manager",
                    'ROLE_OPERATOR' => "Operator"
                ],
                'label' => 'Roles',
                'multiple' => true,
            ])
            ->add('enabled', 'choice', [
                'choices' => [
                    '1' => 'Enable',
                    '0' => 'Disable'
                ],
                'label' => 'Enabled'
            ]);

        if (!$builder->getData()->getId()) {
            $builder->add('save', 'submit', array('label' => 'user.form.create'));
        } else {
            $builder->add('save', 'submit', array('label' => 'user.form.update'));
        }
    }
}
