<?php

namespace App\Service;

use App\Entity\Trick;
use App\Entity\TrickImage;
use App\Entity\TrickVideo;
use App\Repository\TrickRepository;

class TrickService
{
    public function __construct(
        private TrickRepository $trickRepository,
        private FlashMessageService $flashMessageService,
        private FileUploaderService $fileUploaderService,
    ) {
    }

    public function getTrickList(string $nbTrick): Array
    {
        $newTricklList = [];
        $getNbTrick = 10;

        $trickList = $this->trickRepository->findBy([], ['id'=>'DESC'], $getNbTrick, $nbTrick);

        foreach ($trickList as $trick) {
            $newTricklList[$trick->getId()]['name'] = $trick->getName();
            $newTricklList[$trick->getId()]['slug'] = $trick->getSlug();
            $listTrickImages = $trick->getTrickImages();
            foreach ($listTrickImages as $trickImages) {
                if (empty($newTricklList[$trick->getId()]['image']))
                {
                    $newTricklList[$trick->getId()]['image'] = $trickImages->getName();
                }
            }
        }

        $newNbTrick = $nbTrick + 10;

        $trickList = ['listTrick' => $newTricklList, 'nbTrick' => $newNbTrick];

        return $trickList;
    }

    public function createTrick(Trick $trick, Array $imageUpload, Array $videoUpload): void
    {
        if (!is_null($imageUpload)) {
            foreach($imageUpload as $image) {
                $imageName = $this->fileUploaderService->upload($image);
                $trickImage = new TrickImage();
                $trickImage->setName($imageName);
                $trick->addTrickImages($trickImage);
            }
        }

        if (!is_null($videoUpload)) {
            foreach($videoUpload as $video) {
                $trickVideo = new TrickVideo();
                $trickVideo->setLink($video->getLink());
                $trick->addTrickVideos($trickVideo);
            }
        }

        $this->trickRepository->save($trick);
        $this->flashMessageService->createFlashMessage('success', 'Le trick a bien été ajouté');
    }
}
