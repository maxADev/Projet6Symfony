<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\ChangePasswordRequestService;
use App\Service\RegenerateRegistrationTokenService;

class RegenerateTokenService
{
    public function __construct(
        private UserRepository $userRepository,
        private ChangePasswordRequestService $requestChangePasswordService,
        private RegenerateRegistrationTokenService $regenerateRegistrationTokenService,
        private FlashMessageService $flashMessageService,
    ) {
    }

    public function regenerateToken(string $token): void
    {
        $request = $this->userRepository->findByToken($token);

        if (!is_null($request[0])) {
            $user = $request[0];
            if (is_null($user->getRegistrationToken())) {
                $this->requestChangePasswordService->requestChangePassword($user->getEmail());
            } else {
                $this->regenerateRegistrationTokenService->regenerateRegistrationToken($user->getEmail());
            }
            $this->flashMessageService->createFlashMessage('success', 'Un nouveau token vous a été envoyé par email');
        }
    }
}
