<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\ProjectRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class IssueFormType extends AbstractType
{
    protected $security;

    public function __construct(TokenStorageInterface $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $this->security->getToken()->getUser();
        $attributeDefault = [
            'translation_domain' => 'AppBundle',
            'attr' => ['class' => 'form-control']
        ];
        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                $form = $event->getForm();
                $issue = $event->getData();
                if ($issue->getType() === null) {
                    $form->add('type', 'choice', [
                        'choices' => [
                            'Bug' => 'issue.type.bug',
                            'Task' => 'issue.type.task',
                            'Story' => 'issue.type.story'
                        ],
                        'required' => true,
                        'translation_domain' => 'AppBundle'
                    ]);
                }
                if (!$issue->getId() || $issue->getType() == 'Subtask') {
                    $form->add('save', 'submit', ['label' => 'issue.page.create']);
                } else {
                    $form->add('resolution', 'choice', [
                        'choices' => [
                            'Fixed' => 'issue.resolution.fixed',
                            'Won_t_fix' => 'issue.resolution.wontfix',
                            'Cannot_reproduce' => 'issue.resolution.cannotrepr',
                            'Done' => 'issue.resolution.done',
                            'Won_t_done' => 'issue.resolution.wontdone'
                        ],
                        'required' => true,
                        'translation_domain' => 'AppBundle'
                    ])
                        ->add('status', 'choice', [
                            'choices' => [
                                'Open' => 'issue.status.open',
                                'In progress' => 'issue.status.inprogress',
                                'Closed' => 'issue.status.closed'
                            ],
                            'required' => true,
                            'translation_domain' => 'AppBundle'
                        ])
                        ->add('save', 'submit', ['label' => 'issue.page.update']);
                }
            });
        $builder
            ->add('code', 'text', $attributeDefault)
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
                'translation_domain' => 'AppBundle'
            ])
            ->add('project', 'entity', [
                'required' => true,
                'property_path' => 'project',
                'class' => 'AppBundle:Project',
                'property' => 'label',
                'multiple' => false,
                'query_builder' => function (EntityRepository $er) use ($user) {
                    return $er->findByUsers($user, true);
                }
            ])
            ->add('assignee', 'entity', [
                'property_path' => 'assignee',
                'class' => 'UserBundle:User',
                'property' => 'username',
                'multiple' => false,
            ]);


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
