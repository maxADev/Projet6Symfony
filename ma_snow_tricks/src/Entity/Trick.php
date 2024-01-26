<?php

namespace App\Entity;

use App\Repository\TrickRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Util\SlugInterface;
use App\Util\DateInterface;

#[ORM\Entity(repositoryClass: TrickRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Trick implements SlugInterface, DateInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 150)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(length: 250)]
    private ?string $slug = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $creation_date = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $modification_date = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'trick', targetEntity: TrickImage::class, orphanRemoval: true)]
    private Collection $trickImages;

    #[ORM\OneToMany(mappedBy: 'trick', targetEntity: TrickVideo::class, orphanRemoval: true)]
    private Collection $trickVideos;

    #[ORM\OneToMany(mappedBy: 'trick', targetEntity: Comment::class, orphanRemoval: true)]
    private Collection $comments;

    #[ORM\ManyToOne(inversedBy: 'tricks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Group $trick_group = null;

    public function __construct()
    {
        $this->trickImages = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->trickVideos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creation_date;
    }

    public function setCreationDate(\DateTimeInterface $creation_date): static
    {
        $this->creation_date = $creation_date;

        return $this;
    }

    public function getModificationDate(): ?\DateTimeInterface
    {
        return $this->modification_date;
    }

    public function setModificationDate(?\DateTimeInterface $modification_date): static
    {
        $this->modification_date = $modification_date;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, TrickImages>
     */
    public function getTrickImages(): Collection
    {
        return $this->trickImages;
    }

    public function addTrickImages(TrickImage $trickImage): static
    {
        if (!$this->trickImages->contains($trickImage)) {
            $this->trickImages->add($trickImage);
            $trickImage->setTrick($this);
        }

        return $this;
    }

    public function removeTrickImages(TrickImage $trickImage): static
    {
        if ($this->trickImages->removeElement($trickImage)) {
            // set the owning side to null (unless already changed)
            if ($trickImage->getTrick() === $this) {
                $trickImage->setTrick(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, TrickVideo>
     */
    public function getTrickVideos(): Collection
    {
        return $this->trickVideos;
    }

    public function addTrickVideo(TrickVideo $trickVideo): static
    {
        if (!$this->trickVideos->contains($trickVideo)) {
            $this->trickVideos->add($trickVideo);
            $trickVideo->setTrick($this);
        }

        return $this;
    }

    public function removeTrickVideo(TrickVideo $trickVideo): static
    {
        if ($this->trickVideos->removeElement($trickVideo)) {
            // set the owning side to null (unless already changed)
            if ($trickVideo->getTrick() === $this) {
                $trickVideo->setTrick(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setTrick($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getTrick() === $this) {
                $comment->setTrick(null);
            }
        }

        return $this;
    }

    public function getTrickGroup(): ?Group
    {
        return $this->trick_group;
    }

    public function setTrickGroup(?Group $trick_group): static
    {
        $this->trick_group = $trick_group;

        return $this;
    }
}
