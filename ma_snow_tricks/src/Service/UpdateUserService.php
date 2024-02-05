<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class UpdateUserService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private FlashMessageService $flashMessageService,
    ) {
    }

    public function updateUser(User $user): void
    {
        $this->entityManager->flush();
        $this->flashMessageService->createFlashMessage('success', 'Vos informations ont bien été changées');
    }
}
