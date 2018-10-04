<?php

namespace App\Handler\Form;

use Doctrine\ORM\EntityManagerInterface;
use App\Service\FileUploader;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;


class TrickFormHandler
{
    private $entityManager;
    private $fileUploader;
    private $trickImageForwardUploadDir;
    private $trickImageUploadDir;

    public function __construct(EntityManagerInterface $em, FileUploader $fileUploader, ParameterBagInterface $trickImageForwardUploadDir, ParameterBagInterface $trickImageUploadDir)
    {
        $this->entityManager = $em;
        $this->fileUploader = $fileUploader;
        $this->trickImageForwardUploadDir = $trickImageForwardUploadDir->get('trick_image_forward_upload_directory');
        $this->trickImageUploadDir = $trickImageUploadDir->get('trick_image_upload_directory');
    }

    public function detailsHandle($trick, $comment, $commentForm, $user)
    {
        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $comment->setTrick($trick);
            $comment->setAuthor($user);

            $this->entityManager->persist($comment);
            $this->entityManager->flush();

           return true;
        }
    }

    public function editHandle($trick, $trickForm)
    {
        if ($trickForm->isSubmitted() && $trickForm->isValid()) {

            $this->uploadHandle($trick);
            
            $this->entityManager->persist($trick);
            $this->entityManager->flush();

            return true;
          }
    }

    public function addHandle($trick, $trickForm, $user)
    {
        if ($trickForm->isSubmitted() && $trickForm->isValid()) {
            $trickData = $trickForm->getData();
            $slug = $trick->newSlug($trickData->getName());
            
            $trick->setAuthor($user);
            $trick->setSlug($slug);
            $this->uploadHandle($trick);

            $this->entityManager->persist($trick);
            $this->entityManager->flush();

            return true;
        }
    }

    public function uploadHandle($trick): void
    {
        $file = $trick->getImageForward()->getFile();
                
        if ($file !== null) {
            $this->getFileUploader()->setTargetDirectory($this->getTrickImageForwardUploadDir());
            $fileName = $this->getFileUploader()->upload($file);
            $trick->getImageForward()->setFileName($fileName);
        }

        foreach ($trick->getImages()->getValues() as $image)
        {
            $file = $image->getFile();
            if ($file !== null) {
                $this->getFileUploader()->setTargetDirectory($this->getTrickImageUploadDir());
                $fileName = $this->getFileUploader()->upload($file);
                $image->setName($fileName);
            }
        }
    }

    public function getEntityManager()
    {
        return $this->entityManager;
    }

    public function setEntityManager($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getFileUploader()
    {
        return $this->fileUploader;
    }

    public function setFileUploader($fileUploader)
    {
        $this->fileUploader = $fileUploader;
    }

    public function getTrickImageForwardUploadDir()
    {
        return $this->trickImageForwardUploadDir;
    }

    public function setTrickImageForwardUploadDir($trickImageForwardUploadDir)
    {
        $this->trickImageForwardUploadDir = $trickImageForwardUploadDir;
    }

    public function getTrickImageUploadDir()
    {
        return $this->trickImageUploadDir;
    }

    public function setTrickImageUploadDir($trickImageUploadDir)
    {
        $this->trickImageUploadDir = $trickImageUploadDir;
    }
}