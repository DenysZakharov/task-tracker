<?php
namespace AppBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;

use AppBundle\Entity\Issue;
use AppBundle\Entity\Comment;
use AppBundle\Entity\Activity;

class ActivityEventListener
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function preUpdate(PreUpdateEventArgs $eventArgs)
    {
        $issue = $eventArgs->getEntity();
        $entityManager = $eventArgs->getEntityManager();

        if ($issue instanceof Issue) {
            $user = $this->container->get('security.context')->getToken()->getUser();

            if ($eventArgs->hasChangedField('status')) {
                $this->setActivity($issue, $user, Activity::CHANGE_ISSUE_STATUS, $entityManager);
            }
        }
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $entityManager = $args->getEntityManager();

        if ($entity instanceof Comment) {
            $issue = $entity->getIssue();
            $this->setActivity($issue, $entity->getAuthor(), Activity::COMMENT_ISSUE, $entityManager);
        }

        if ($entity instanceof Issue) {
            $user = $this->container->get('security.context')->getToken()->getUser();
            $this->setActivity($entity, $user, Activity::CREATE_ISSUE, $entityManager);
        }

        if ($entity instanceof Activity) {
            $collaborators = $entity->getIssue()->getCollaborators();
            $emails = [];
            foreach ($collaborators as $collaborator) {
                $emails[] = $collaborator->getEmail();
            }

            $message = \Swift_Message::newInstance()
                ->setSubject('Issue activity: ' . $entity->getIssue()->getSummary())
                ->setFrom('send@example.com')
                ->setTo($emails)
                ->setBody(
                    $this->container->get('templating')->render(
                        'AppBundle:Activity:activity.html.twig',
                        ['activities' => $entity]
                    ),
                    'text/html'
                );

            $this->container->get('mailer')->send($message);
        }
    }

    private function setActivity($issue, $user, $type, $entityManager)
    {
        $entity = new Activity();
        $entity
            ->setIssue($issue)
            ->setProject($issue->getProject())
            ->setUser($user)
            ->setType($type);
        $entityManager->persist($entity);
        $entityManager->flush();
    }
}