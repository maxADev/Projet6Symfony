<?php

// src/Controller/CommentController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use App\Entity\Comment;
use App\Entity\Trick;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use App\Service\CreateCommentService;
use App\Form\Type\CommentFormType;

class CommentController extends AbstractController
{
    #[Route('/comment-form', name: 'comment_form')]
    public function commentForm(Trick $trick): Response
    {
        $form = $this->createForm(CommentFormType::class);

        return $this->render('comment/commentForm.html.twig', [
            'form' => $form,
            'trick' => $trick,
        ]);
    }

    #[Route('/create-comment/{slug}/new', name: 'create_comment')]
    public function createComment(#[CurrentUser] User $user, Trick $trick, Request $request, CreateCommentService $createCommentService): Response
    {
        $comment = new Comment();

        $form = $this->createForm(CommentFormType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $createCommentService->createComment($user, $trick, $comment);
            return $this->redirectToRoute('trick', ['slug' => $trick->getSlug()]);
        }

        return $this->render('comment/commentFormError.html.twig', [
            'trick' => $trick,
            'form' => $form,
        ]);
    }
}
