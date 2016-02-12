<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class IssueFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $attributeDefault = [
            'translation_domain' => 'AppBundle',
            'attr' => ['class' => 'form-control']
        ];

        $builder
            ->add('summary', 'text', $attributeDefault)
            ->add('description', 'textarea', $attributeDefault)
            ->add('priority', 'choice', [
                'choices' => [
                    'Trivial' => 'issue.priority.trivial',
                    'Minor' => 'issue.priority.minor',
                    'Major' => 'issue.priority.major',
                    'Critical' => 'issue.priority.critical',
                    'Blocker' => 'issue.priority.blocker'
                ],
                'required' => true,
                'label' => 'issue.priority.label',
                'translation_domain' => 'AppBundle'
            ])
            ->add('type', 'choice', [
                'choices' => [
                    'Bug' => 'issue.type.bug',
                    'Subtask' => 'issue.type.subtask',
                    'Task' => 'issue.type.task',
                    'Story' => 'issue.type.story'
                ],
                'required' => true,
                'label' => 'issue.type.label',
                'translation_domain' => 'AppBundle'
            ])
            ->add('resolution', 'choice', [
                'choices' => [
                    'Fixed' => 'issue.resolution.fixed',
                    'Won_t_fix' => 'issue.resolution.wontfix',
                    'Cannot_reproduce' => 'issue.resolution.cannotrepr',
                    'Done' => 'issue.resolution.done',
                    'Won_t_done' => 'issue.resolution.wontdone'
                ],
                'required' => true,
                'label' => 'issue.resolution.label',
                'translation_domain' => 'AppBundle'
            ])
            ->add('status', 'choice', [
                'choices' => [
                    'Open' => 'issue.status.open',
                    'In progress' => 'issue.status.inprogress',
                    'Closed' => 'issue.status.closed'
                ],
                'required' => true,
                'label' => 'issue.status.label',
                'translation_domain' => 'AppBundle'
            ])
            ->add('priority', 'choice', [
                'choices' => [
                    'Trivial' => 'issue.required.trivial',
                    'Minor' => 'issue.required.minor',
                    'Major' => 'issue.required.major',
                    'Critical' => 'issue.required.critical',
                    'Blocker' => 'issue.required.blocker'
                ],
                'required' => true,
                'label' => 'issue.required.label',
                'translation_domain' => 'AppBundle'
            ])
            ->add('assignee', 'entity', array(
                'property_path' => 'assignee',
                'class' => 'UserBundle:User',
                'property' => 'username',
                'multiple' => false,
                'attr' => array('class'=>'form-control')
            ));
        $builder->add('project', 'entity', array(
            'required' => true,
            'property_path' => 'project',
            'class' => 'AppBundle:Project',
            'property' => 'label',
            'multiple' => false,
            'attr' => array('class'=>'form-control')
        ));
        if (!$builder->getData()->getId()) {
            $builder->add('save', 'submit', ['label' => 'issue.page.create']);
        } else {
            $builder->add('save', 'submit', ['label' => 'issue.page.update']);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => 'AppBundle\Entity\Issue']);
    }

    public function getName()
    {
        return 'app_issue';
    }

    public function setProject(Project $project)
    {
        $this->project = $project;
    }
}
