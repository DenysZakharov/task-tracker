<?php
namespace AppBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;

use AppBundle\Entity\Issue;

class CollaboratorEventListener
{
    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof Issue) {
            if ($this->hasChangedField($args, 'assignee')) {
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
    }

    private function hasChangedField(PreUpdateEventArgs $eventArgs, $field)
    {
        return $eventArgs->hasChangedField($field);
    }
}