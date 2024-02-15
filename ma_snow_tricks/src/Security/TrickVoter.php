<?php

// src/Security/TrickVoter.php
namespace App\Security;

use App\Entity\Trick;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class TrickVoter extends Voter
{
    // these strings are just invented: you can use anything
    const VIEW = 'view';
    const EDIT = 'edit';

    protected function supports(string $attribute, mixed $subject): bool
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, [self::VIEW, self::EDIT])) {
            return false;
        }

        // only vote on `Trick` objects
        if (!$subject instanceof Trick) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            // the user must be logged in; if not, deny access
            return false;
        }

        // you know $subject is a Trick object, thanks to `supports()`
        /** @var Trick $trick */
        $trick = $subject;

        return match($attribute) {
            self::VIEW => $this->canView($trick, $user),
            self::EDIT => $this->canEdit($trick, $user),
            default => throw new \LogicException('This code should not be reached!')
        };
    }

    private function canView(Trick $trick, User $user): bool
    {
        // if they can edit, they can view
        if ($this->canEdit($trick, $user)) {
            return true;
        }

        // the Trick object could have, for example, a method `isPrivate()`
        return !$trick->isPrivate();
    }

    private function canEdit(Trick $trick, User $user): bool
    {
        // this assumes that the Trick object has a `getOwner()` method
        return $user === $trick->getOwner();
    }
}