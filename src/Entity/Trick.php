<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TrickRepository")
 * @UniqueEntity(
 *     fields={"name"},
 *     message="Figure déjà existante !")
 */
class Trick
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank()
     * @Assert\Type("string")
     * @Assert\Length(
     *      min = 5,
     *      max = 100,
     *      minMessage = "Le nom d'une figure doit être composé d'au moins 5 caractères.",
     *      maxMessage = "Le nom d'une figure ne peut pas dépasser 100 caractères.")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $slug;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     * @Assert\Type("string")
     * @Assert\Length(
     *      min = 20,
     *      minMessage = "La description d'une figure doit être composé d'au moins 20 caractères.")
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     */
    private $publishDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $editDate;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TrickGroup")
     * @ORM\JoinColumn(nullable=false)
     */
    private $trickGroup;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="trick", orphanRemoval=true)
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Image", mappedBy="trick", cascade="all", orphanRemoval=true)
     */
    private $images;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Video", mappedBy="trick", cascade="all", orphanRemoval=true)
     */
    private $videos;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\ImageForward", mappedBy="trick", cascade={"persist", "remove"})
     */
    private $imageForward;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->videos = new ArrayCollection();
        $this->publishDate = new \Datetime();
        $this->imageForward = new ImageForward();
        $this->imageForward->setFileName('default.jpg');
        $this->imageForward->setTrick($this);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function newSlug($name)
    {
        $name = preg_replace('#[^\\pL\d]+#u', '-', $name);
        $name = trim($name, '-');

        if (function_exists('iconv')) {
            $name = iconv('utf-8', 'us-ascii//TRANSLIT', $name);
        }

        $name = strtolower($name);
        $name = preg_replace('#[^-\w]+#', '', $name);

        if (empty($name)) {
            return 'n-a';
        }

        return $name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPublishDate(): ?\DateTimeInterface
    {
        return $this->publishDate;
    }

    public function setPublishDate(\DateTimeInterface $publishDate): self
    {
        $this->publishDate = $publishDate;

        return $this;
    }

    public function getEditDate(): ?\DateTimeInterface
    {
        return $this->editDate;
    }

    public function setEditDate(?\DateTimeInterface $editDate): self
    {
        $this->editDate = $editDate;

        return $this;
    }

    public function getTrickGroup(): ?TrickGroup
    {
        return $this->trickGroup;
    }

    public function setTrickGroup(?TrickGroup $trickGroup): self
    {
        $this->trickGroup = $trickGroup;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection|Comments[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setTrick($this);
        }

        return $this;
    }

    /**
     * @return Collection|Image[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image)
    {
        $this->images[] = $image;
        // setting the current user to the $exp,
        // adapt this to whatever you are trying to achieve
        $image->setTrick($this);

        return $this;
    }

    public function removeImage(Image $image)
    {
        $this->images->removeElement($image);
    }

    /**
     * @return Collection|Video[]
     */
    public function getVideos(): Collection
    {
        return $this->videos;
    }

    public function addVideo(Video $video)
    {
        $this->videos[] = $video;
        // setting the current user to the $exp,
        // adapt this to whatever you are trying to achieve
        $video->setTrick($this);

        return $this;
    }

    public function removeVideo(Video $video)
    {
        $this->videos->removeElement($video);
    }

    public function getImageForward(): ?ImageForward
    {
        return $this->imageForward;
    }

    public function setImageForward(ImageForward $imageForward): self
    {
        $this->imageForward = $imageForward;

        // set the owning side of the relation if necessary
        if ($this !== $imageForward->getTrick()) {
            $imageForward->setTrick($this);
        }

        return $this;
    }
}
