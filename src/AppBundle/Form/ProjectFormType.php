<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $attributeDefault = [
            'label_attr' => ['class' => 'col-sm-4 control-label'],
            'translation_domain' => 'UserBundle',
            'attr'=>['class'=> 'form-control']
        ];

        $builder
            ->add('label', 'text', $attributeDefault)
            ->add('summary', 'textarea', $attributeDefault)
            ->add('code', 'text', $attributeDefault)
            ->add('users', null, $attributeDefault);
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
