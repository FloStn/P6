<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ImageForwardRepository")
 */
class ImageForward
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $fileName;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Trick", inversedBy="imageForward", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $trick;

    /**
     * @Assert\File(mimeTypes={ "image/jpg" })
     */
    private $file;

    public function __construct()
    {
        $this->setFileName("default.jpg");
    }

    public function getId()
    {
        return $this->id;
    }

    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    public function setFileName(string $fileName): self
    {
        $this->fileName = $fileName;

        return $this;
    }

    public function getTrick(): ?Trick
    {
        return $this->trick;
    }

    public function setTrick(Trick $trick): self
    {
        $this->trick = $trick;

        return $this;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }
}
