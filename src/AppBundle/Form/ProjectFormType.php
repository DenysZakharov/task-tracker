<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use UserBundle\Entity\User;

class ProjectFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $attributeDefault = [
            'translation_domain' => 'AppBundle'
        ];

        $builder
            ->add('label', 'text', $attributeDefault)
            ->add('summary', 'textarea', $attributeDefault)
            ->add('code', 'text', $attributeDefault)
            ->add('users', 'entity', [
                'required' => true,
                'class' => 'UserBundle:User',
                'property' => 'username',
                'multiple' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => 'AppBundle\Entity\Project']);
    }

    public function getName()
    {
        return 'app_project';
    }
}
