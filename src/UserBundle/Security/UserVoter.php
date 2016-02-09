<?php

namespace UserBundle\Security;

use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

use UserBundle\Entity\User;

class UserVoter implements VoterInterface
{
    const VIEW = 'view';
    const EDIT = 'edit';
    const CREATE = 'create';

    /**
     * @param string $attribute
     *
     * @return bool
     */
    public function supportsAttribute($attribute)
    {
        return in_array($attribute, array(
            self::VIEW,
            self::EDIT,
            self::CREATE
        ), false);
    }

    /**
     * @param string $class
     *
     * @return bool
     */
    public function supportsClass($class)
    {
        $supportedClass = 'UserBundle\Entity\User';

        return $supportedClass === $class || is_subclass_of($class, $supportedClass);
    }

    public function vote(TokenInterface $token, $user, array $attributes)
    {
        if (!$this->supportsClass(get_class($user))) {
            return self::ACCESS_ABSTAIN;
        }

        if (1 !== count($attributes)) {
            throw new \InvalidArgumentException('Only one attribute is allowed for VIEW or EDIT or CREATE');
        }

        $attribute = $attributes[0];

        if (!$this->supportsAttribute($attribute)) {
            return self::ACCESS_ABSTAIN;
        }

        $currentUser = $token->getUser();

        if (!$user instanceof User) {
            return self::ACCESS_DENIED;
        }

        if ($user === $currentUser || $currentUser->hasRole(User::ROLE_ADMIN)) {
            return self::ACCESS_GRANTED;
        }
        if ($currentUser->hasRole(User::ROLE_OPERATOR) && $attribute == self::VIEW) {
            return self::ACCESS_GRANTED;
        }

        return self::ACCESS_DENIED;
    }
}
