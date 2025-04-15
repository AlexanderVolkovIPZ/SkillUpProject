<?php

namespace App\Security;

use Exception;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class LoginChecker implements UserCheckerInterface
{
    /**
     * @param UserInterface $user
     * @return true
     * @throws Exception
     */
    public function checkPreAuth(UserInterface $user): bool
    {
        if (!$user instanceof User) {
            throw new Exception('Something went wrong', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        if (!$user->getIsConfirmed()) {
            throw new Exception('Please, activate your account!', Response::HTTP_UNAUTHORIZED);
        }
        
        return true;
    }

    /**
     * @param UserInterface $user
     * @return void
     */
    public function checkPostAuth(UserInterface $user):void
    {
        // TODO: Implement checkPostAuth() method.
    }
}