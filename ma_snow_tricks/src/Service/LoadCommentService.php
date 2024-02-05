<?php

namespace App\Service;

use App\Entity\Trick;
use App\Entity\Comment;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CommentRepository;

class LoadCommentService
{
    public function __construct(
        private CommentRepository $commentRepository,
    ) {
    }

    public function getCommentList(Trick $trick, int $nbComment): Array
    {
        $newCommentlList = [];
        $listComment = $this->commentRepository->getCommentPaginator($trick, $nbComment);

        foreach ($listComment as $comment) {
            $creationDate = $comment->getCreationDate();
            $newCommentlList[$comment->getId()]['content'] = $comment->getContent();
            $newCommentlList[$comment->getId()]['createDate'] = $creationDate->format('d/m/Y à H:i');
            $newCommentlList[$comment->getId()]['userName'] = $comment->getUser()->getName();
            $newCommentlList[$comment->getId()]['userImage'] = $comment->getUser()->getImage();
        }

        $newNbComment = $nbComment + 10;

        $commentList = ['listComment' => $newCommentlList, 'nbComment' => $newNbComment];

        return $commentList;
    }
}
