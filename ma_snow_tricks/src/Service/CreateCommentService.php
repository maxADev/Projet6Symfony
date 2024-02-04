<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\Trick;
use App\Entity\Comment;
use Doctrine\ORM\EntityManagerInterface;

class CreateCommentService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private FlashMessageService $flashMessageService,
    ) {
    }

    public function createComment(User $user, Trick $trick, Comment $comment): void
    {
        $comment->setUser($user);
        $trick->addComment($comment);
        $this->entityManager->persist($comment);
        $this->entityManager->flush();

        $this->flashMessageService->createFlashMessage('success', 'Le commentaire a bien été ajouté');
    }
}
