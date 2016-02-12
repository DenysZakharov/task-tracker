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
            'translation_domain' => 'AppBundle',
            'attr'=>['class'=> 'form-control']
        ];

        $builder
            ->add('label', 'text', $attributeDefault)
            ->add('summary', 'textarea', $attributeDefault)
            ->add('code', 'text', $attributeDefault)
            ->add('users', null, $attributeDefault);
        if (!$builder->getData()->getId()) {
            $builder->add('save', 'submit', ['label' => 'project.page.create']);
        } else {
            $builder->add('save', 'submit', ['label' => 'project.page.update']);
        }
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
