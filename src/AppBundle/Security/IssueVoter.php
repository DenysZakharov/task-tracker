<?php

namespace AppBundle\Security;

use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

use AppBundle\Entity\Project;
use AppBundle\Entity\Issue;
use UserBundle\Entity\User;

class IssueVoter implements VoterInterface
{
    const VIEW = 'view';
    const EDIT = 'edit';
    const CREATE = 'create';
    const ADD_SUB_TASK = 'add_sub_task';

    public function supportsAttribute($attribute)
    {
        return in_array($attribute, array(
            self::VIEW,
            self::EDIT,
            self::CREATE,
            self::ADD_SUB_TASK
        ), false);
    }

    public function supportsClass($class)
    {
        $supportedClass = 'AppBundle\Entity\Issue';

        return $supportedClass === $class || is_subclass_of($class, $supportedClass);
    }

    public function vote(TokenInterface $token, $issue, array $attributes)
    {
        if (!$this->supportsClass(get_class($issue))) {
            return self::ACCESS_ABSTAIN;
        }

        if (1 !== count($attributes)) {
            throw new \InvalidArgumentException('Only one attribute is allowed for VIEW or EDIT or CREATE');
        }

        $attribute = $attributes[0];

        if (!$this->supportsAttribute($attribute)) {
            return self::ACCESS_ABSTAIN;
        }

        $user = $token->getUser();

        if (!$user instanceof User) {
            return self::ACCESS_DENIED;
        }

        if ($issue->getProject()) {
            $isMemberProject = $issue->getProject()->isMember($user);
        } else {
            $isMemberProject = $user->getProject()->count() > 0;
        }

        if ($user->hasRole(User::ROLE_ADMIN) || $user->hasRole(User::ROLE_MANAGER) || $isMemberProject) {
            return self::ACCESS_GRANTED;
        }

        return self::ACCESS_DENIED;
    }
}
