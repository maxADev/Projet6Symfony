<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\Trick;
use App\Entity\Comment;
use App\Repository\CommentRepository;

class CommentService
{
    public function __construct(
        private CommentRepository $commentRepository,
        private FlashMessageService $flashMessageService,
    ) {
    }

    public function createComment(Comment $comment): void
    {
        $this->commentRepository->save($comment);
        $this->flashMessageService->createFlashMessage('success', 'Le commentaire a bien été ajouté');
    }

    public function getCommentList(Trick $trick, int $nbComment): Array
    {
        $newCommentlList = [];
        $listComment = $this->commentRepository->getCommentPaginator($trick, $nbComment);

        foreach ($listComment as $comment) {
            $commentId = $comment->getId();
            $creationDate = $comment->getCreationDate();
            $newCommentlList[$commentId]['content'] = $comment->getContent();
            $newCommentlList[$commentId]['createDate'] = $creationDate->format('d/m/Y à H:i');
            $newCommentlList[$commentId]['userName'] = $comment->getUser()->getName();
            $newCommentlList[$commentId]['userImage'] = $comment->getUser()->getImage();
        }

        $newNbComment = $nbComment + 10;

        $commentList = ['listComment' => $newCommentlList, 'nbComment' => $newNbComment];

        return $commentList;
    }
}