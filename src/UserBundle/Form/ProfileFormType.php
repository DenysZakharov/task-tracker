<?php

namespace UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;


class ProfileFormType extends AbstractType
{
    public function getName()
    {
        return 'profile';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username', null, ['label' => 'form.username', 'translation_domain' => 'FOSUserBundle'])
            ->add('email', 'email', ['label' => 'form.email', 'translation_domain' => 'FOSUserBundle'])
            ->add('current_password', 'password', [
                'label' => 'form.current_password',
                'translation_domain' => 'FOSUserBundle',
                'mapped' => false,
                'constraints' => new UserPassword()
            ])
            ->add('fullName', null, [
                'label_attr' => ['class' => 'col-sm-4 control-label'],
                'translation_domain' => 'FOSUserBundle',
                'attr' => ['class' => 'form-control']
            ]);
    }

    public function getParent()
    {
        return 'fos_user_profile';
    }
}
