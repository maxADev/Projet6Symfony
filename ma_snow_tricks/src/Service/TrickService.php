<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\Trick;
use App\Entity\TrickImage;
use App\Entity\TrickVideo;
use App\Repository\TrickRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;

class TrickService
{
    public function __construct(
        private TrickRepository $trickRepository,
        private FlashMessageService $flashMessageService,
        private FileUploaderService $fileUploaderService,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function getTrickList(string $nbTrick, ?User $user): Array
    {
        $newTricklList = [];
        $getNbTrick = 10;

        $trickList = $this->trickRepository->findBy([], ['id'=>'DESC'], $getNbTrick, $nbTrick);

        foreach ($trickList as $trick) {
            $modify = false;
            $image = null;
            if(!is_null($user)) {
                if ($user->getId() === $trick->getUser()->getId()) {
                    $modify = true;
                }
            }
            $listTrickImages = $trick->getTrickImages();
            foreach ($listTrickImages as $trickImages) {
                $image = $trickImages->getName();
                break;
            }
            $newTricklList[] = ['name' => $trick->getName(),
                                'slug' => $trick->getSlug(),
                                'modify' => $modify,
                                'image' => $image,
                               ];
        }

        $newNbTrick = $nbTrick + 10;

        $trickList = ['listTrick' => $newTricklList, 'nbTrick' => $newNbTrick];

        return $trickList;
    }

    public function manageTrick(Trick $trick, array $imageUpload, string $message): void
    {
        if (!is_null($imageUpload)) {
            foreach($imageUpload as $image) {
                $imageName = $this->fileUploaderService->upload($image);
                $trickImage = new TrickImage();
                $trickImage->setName($imageName);
                $trick->addTrickImage($trickImage);
            }
        }

        $this->trickRepository->save($trick);
        $this->flashMessageService->createFlashMessage('success', $message);
    }
}
