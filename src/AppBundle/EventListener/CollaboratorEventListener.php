<?php
namespace AppBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;

use AppBundle\Entity\Issue;
use AppBundle\Entity\Comment;
use AppBundle\Entity\User;

class CollaboratorEventListener
{
    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof Issue) {
            if ($args->hasChangedField('assignee')) {
                $entity->addCollaborator($entity->getAssignee());
            }
        }
    }

    public function postUpdate(LifecycleEventArgs $event)
    {
        $event->getEntityManager()->flush();
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof Issue) {
            if (!$entity->getCollaborators()->contains($entity->getReporter())) {
                $entity->addCollaborator($entity->getReporter());
            }

            if (!$entity->getCollaborators()->contains($entity->getAssignee())) {
                $entity->addCollaborator($entity->getAssignee());
            }
        }

        if ($entity instanceof Comment) {
            $issue = $entity->getIssue();
            if (!$issue->getCollaborators()->contains($entity->getAuthor())) {
                $issue->addCollaborator($entity->getAuthor());
            }
        }
    }
}