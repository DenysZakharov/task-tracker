<?php

namespace AppBundle\Security;

use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

use AppBundle\Entity\Project;
use UserBundle\Entity\User;

class ProjectVoter implements VoterInterface
{
    const VIEW = 'view';
    const EDIT = 'edit';
    const CREATE = 'create';

    public function supportsAttribute($attribute)
    {
        return in_array($attribute, array(
            self::VIEW,
            self::EDIT,
            self::CREATE
        ), false);
    }

    public function supportsClass($class)
    {
        $supportedClass = 'AppBundle\Entity\Project';

        return $supportedClass === $class || is_subclass_of($class, $supportedClass);
    }

    public function vote(TokenInterface $token, $project, array $attributes)
    {
        if (!$this->supportsClass(get_class($project))) {
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

        if ($project->getUsers()->count() > 0) {
            $isMemberProject = $project->isMember($user);
        } else {
            $isMemberProject = $user->getProject()->count() > 0;
        }

        if ($isMemberProject && $attribute == self::VIEW) {
            return self::ACCESS_GRANTED;
        }

        if ($user->hasRole(User::ROLE_MANAGER) || $user->hasRole(User::ROLE_ADMIN)) {
            return self::ACCESS_GRANTED;
        }

        return self::ACCESS_DENIED;
    }
}
