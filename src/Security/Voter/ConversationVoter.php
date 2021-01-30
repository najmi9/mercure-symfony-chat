<?php

declare(strict_types=1);

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class ConversationVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['CONV_EDIT', 'CONV_VIEW'])
            && $subject instanceof \App\Entity\Conversation;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
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
        }

        return false;
    }
}
