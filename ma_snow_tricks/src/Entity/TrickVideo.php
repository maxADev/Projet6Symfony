<?php

namespace App\Entity;

use App\Repository\TrickVideoRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TrickVideoRepository::class)]
class TrickVideo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 150)]
    private ?string $link = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Trick $FK_trick = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(string $link): static
    {
        $this->link = $link;

        return $this;
    }

    public function getFKTrick(): ?Trick
    {
        return $this->FK_trick;
    }

    public function setFKTrick(?Trick $FK_trick): static
    {
        $this->FK_trick = $FK_trick;

        return $this;
    }
}
