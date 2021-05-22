<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\Message;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class MessageVoter extends Voter
{
    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, ['DELETE_MSG', 'EDIT_MSG'])
            && $subject instanceof Message;
    }

    /**
     * @param string $attribute
     * @param Message $subject
     * @param TokenInterface $token
     * @return boolean
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        /** @var User $user */
        $user = $token->getUser();
        if (!$user instanceof UserInterface) {
            return false;
        }

        $canDelete = $subject->getUser()->getId() === $user->getId() || in_array('ROLE_ADMIN', $user->getRoles());

        switch ($attribute) {
            case 'DELETE_MSG':
                return $canDelete;
            case 'EDIT_MSG':
                return $canDelete;
        }

        return false;
    }
}
