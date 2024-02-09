<?php

// src/Controller/CommentController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\User;
use App\Entity\Comment;
use App\Entity\Trick;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use App\Service\CommentService;
use App\Form\Type\CommentFormType;

class CommentController extends AbstractController
{
    #[Route('/create-comment/{slug}', name: 'create_comment')]
    public function createComment(#[CurrentUser] User $user, Request $request, Trick $trick, CommentService $commentService): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentFormType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setUser($user);
            $trick->addComment($comment);
            $commentService->createComment($comment);
            return $this->redirectToRoute('trick', ['slug' => $trick->getSlug()]);
        }

        return $this->render('comment/commentForm.html.twig', [
            'form' => $form,
            'trick' => $trick,
        ]);
    }

    #[Route('/get-comment/{slug}', name: 'get_comment')]
    public function loadComment(Request $request, CommentService $commentService, Trick $trick): JsonResponse
    {
        $nbComment = intval($request->request->get('nbComment'));
        $commentlList = $commentService->getCommentList($trick, $nbComment);

        return new JsonResponse(['listComment' => $commentlList['listComment'], 'nbComment' => $commentlList['nbComment']]);
    }
}
