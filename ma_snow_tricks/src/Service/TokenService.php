<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use DateTime;

class TokenService
{
    public function __construct(
        private UserRepository $userRepository,
        private EmailService $emailService,
        private PasswordService $passwordService,
    ) {
    }

    public function checkValidationToken(DateTime $token): bool
    {
        $return = true;
        $tokenCreatedAt = $token;
        $checkTime = new \DateTime();
        $difference = $tokenCreatedAt->diff($checkTime);
        $differenceValue = intval($difference->format('%I') + ($difference->format('%H') * 60));

        if ($differenceValue > 15) {
           $return = false;
        }

        return $return;
    }

    public function regenerateToken(string $token): void
    {
        $request = $this->userRepository->findByToken($token);

        if (!is_null($request[0])) {
            $user = $request[0];
            if (is_null($user->getRegistrationToken())) {
                $this->passwordService->requestChangePassword($user->getEmail());
            } else {
                $this->regenerateRegistrationToken($user->getEmail());
            }
        }
    }

    public function regenerateRegistrationToken(string $email): void
    {
        $user = $this->userRepository->findOneBy(['email' => $email]);

        if (!$user) {
            return;
        }

        $user->generateRegistrationToken();
        $user->setRegistrationTokenDate();
        $this->userRepository->save($user);

        $this->emailService->sendEmailRegistration($user->getEmail(), $user->getRegistrationToken());
    }
}
