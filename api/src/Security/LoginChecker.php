<?php

namespace App\Security;

use Exception;
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
        if (!$user->getIsConfirmed()) {
            throw new Exception('Please, activate your account!', Response::HTTP_UNAUTHORIZED);
        }
        return true;
    }

    /**
     * @param UserInterface $user
     * @return void
     */
    public function checkPostAuth(UserInterface $user)
    {
        // TODO: Implement checkPostAuth() method.
    }

}