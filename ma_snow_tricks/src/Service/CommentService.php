<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\Trick;
use App\Entity\Comment;
use App\Repository\CommentRepository;
use DateTime;

class CommentService
{
    public function __construct(
        private CommentRepository $commentRepository,
        private FlashMessageService $flashMessageService,
    ) {
    }

    public function createComment(Comment $comment): void
    {
        $comment->setCreationDate(new DateTime());
        $this->commentRepository->save($comment);
        $this->flashMessageService->createFlashMessage('success', 'Le commentaire a bien été ajouté');
    }

    public function getCommentList(Trick $trick, int $nbComment): Array
    {
        $newCommentlList = [];
        $listComment = $this->commentRepository->getCommentPaginator($trick, $nbComment);

        foreach ($listComment as $comment) {
            $creationDate = $comment->getCreationDate();
            $newCommentlList[] = ['content' => $comment->getContent(),
                                  'createDate' => $creationDate->format('d/m/Y à H:i'),
                                  'userName' => $comment->getUser()->getName(),
                                  'userImage' => $comment->getUser()->getImage(),
                                ];
        }

        $newNbComment = $nbComment + 10;

        $commentList = ['listComment' => $newCommentlList, 'nbComment' => $newNbComment];

        return $commentList;
    }
}
