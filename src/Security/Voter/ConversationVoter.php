<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\Conversation;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class ConversationVoter extends Voter
{
    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, ['CONV_EDIT', 'CONV_VIEW', 'CONV_DELETE'])
            && $subject instanceof Conversation;
    }

    /**
     * @param string $attribute
     * @param Conversation $subject
     * @param TokenInterface $token
     * @return void
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        /** @var User $user */
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        switch ($attribute) {
            case 'CONV_EDIT':
                $usersConv = $subject->getUsers()->getValues();
                if (in_array($user, $usersConv)) {
                    return true;
                }

                return false;

            case 'CONV_VIEW':
                $usersConv = $subject->getUsers()->getValues();
                if (in_array($user, $usersConv)) {
                    return true;
                }

                return false;

            case 'CONV_DELETE':
                $usersConv = $subject->getUsers()->getValues();
                if (in_array($user, $usersConv) && $subject->getOwnerId() === $user->getId()) {
                    return true;
                }

                return false;
        }

        return false;
    }
}
