<?php

namespace AppBundle\Security;

use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

use AppBundle\Entity\Comment;
use UserBundle\Entity\User;

class CommentVoter implements VoterInterface
{
    const EDIT = 'edit';
    const DELETE = 'delete';

    public function supportsAttribute($attribute)
    {
        return in_array($attribute, [
            self::EDIT,
            self::DELETE,
        ]);
    }

    public function supportsClass($class)
    {
        $supportedClass = 'AppBundle\Entity\Comment';
        return is_a($supportedClass, $class, true);
    }

    public function vote(TokenInterface $token, $comment, array $attributes)
    {
        if (!$this->supportsClass(get_class($comment))) {
            return self::ACCESS_ABSTAIN;
        }

        if (1 !== count($attributes)) {
            throw new \InvalidArgumentException('Only one attribute is allowed for EDIT or CREATE');
        }

        $attribute = $attributes[0];

        if (!$this->supportsAttribute($attribute)) {
            return self::ACCESS_ABSTAIN;
        }

        $user = $token->getUser();

        if (!$user instanceof User) {
            return self::ACCESS_DENIED;
        }

        if ($user->hasRole(User::ROLE_ADMIN) || ($user->getId() === $comment->getAuthor()->getId())) {
            return self::ACCESS_GRANTED;
        }

        return self::ACCESS_ABSTAIN;
    }
}
