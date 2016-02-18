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
use AppBundle\Entity\Mapping\EnumPriorityIssue;
use AppBundle\Entity\Mapping\EnumTypeIssue;
use AppBundle\Entity\Mapping\EnumStatusIssue;
use AppBundle\Entity\Mapping\EnumResolutionIssue;

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
            'translation_domain' => 'AppBundle'
        ];
        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                $form = $event->getForm();
                $issue = $event->getData();
                if ($issue->getType() === null) {
                    $form->add('type', 'choice', [
                        'choices' => [
                            EnumTypeIssue::BUG => 'issue.type.bug',
                            EnumTypeIssue::TASK => 'issue.type.task',
                            EnumTypeIssue::STORY => 'issue.type.story'
                        ],
                        'required' => true,
                        'translation_domain' => 'AppBundle'
                    ]);
                }
                if ($issue->getType() !== null && $issue->getType() != EnumTypeIssue::SUBTASK) {
                    $form->add('resolution', 'choice', [
                        'choices' => [
                            EnumResolutionIssue::FIXED => 'issue.resolution.fixed',
                            EnumResolutionIssue::WONTFIX => 'issue.resolution.wontfix',
                            EnumResolutionIssue::CANNOT_REPRODUCE => 'issue.resolution.cannotrepr',
                            EnumResolutionIssue::DONE => 'issue.resolution.done',
                            EnumResolutionIssue::WONTDONE => 'issue.resolution.wontdone'
                        ],
                        'required' => true,
                        'translation_domain' => 'AppBundle'
                    ])
                        ->add('status', 'choice', [
                            'choices' => [
                                EnumStatusIssue::OPEN => 'issue.status.open',
                                EnumStatusIssue::INPROGRESS => 'issue.status.inprogress',
                                EnumStatusIssue::CLOSED => 'issue.status.closed'
                            ],
                            'required' => true,
                            'property' => 'value',
                            'translation_domain' => 'AppBundle'
                        ]);
                }
            });
        $builder
            ->add('code', 'text', $attributeDefault)
            ->add('summary', 'text', $attributeDefault)
            ->add('description', 'textarea', $attributeDefault)
            ->add('priority', 'choice', [
                'choices' => [
                    EnumPriorityIssue::TRIVIAL => 'issue.priority.trivial',
                    EnumPriorityIssue::MINOR => 'issue.priority.minor',
                    EnumPriorityIssue::MAJOR => 'issue.priority.major',
                    EnumPriorityIssue::CRITICAL => 'issue.priority.critical',
                    EnumPriorityIssue::BLOCKER => 'issue.priority.blocker'
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
}
