<?php

namespace AppBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

use UserBundle\Entity\User;

/**
 * IssueRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class IssueRepository extends EntityRepository
{
    public function findByUser(User $user)
    {
        $query = $this->createQueryBuilder('issue');

        $query->leftJoin('issue.project', 'project')
            ->leftJoin('project.users', 'user')
            ->where('user.id = :user_id')
            ->andWhere('issue.assignee = :user_id')
            ->setParameter('user_id', $user->getId());

        return $query->getQuery()->getResult();
    }
}