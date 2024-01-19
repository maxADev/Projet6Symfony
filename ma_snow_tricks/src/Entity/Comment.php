<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
class Comment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 250)]
    private ?string $content = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $creation_date = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $FK_user = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Trick $FK_trick = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): int
    {
        $this->id = $id;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): string
    {
        $this->content = $content;

        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creation_date;
    }

    public function setCreationDate(\DateTimeInterface $creation_date): \DateTimeInterface
    {
        $this->creation_date = $creation_date;

        return $this;
    }

    public function getFKUser(): ?User
    {
        return $this->FK_user;
    }

    public function setFKUser(?User $FK_user): int
    {
        $this->FK_user = $FK_user;

        return $this;
    }

    public function getFKTrick(): ?Trick
    {
        return $this->FK_trick;
    }

    public function setFKTrick(?Trick $FK_trick): int
    {
        $this->FK_trick = $FK_trick;

        return $this;
    }
}
