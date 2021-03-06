<?php

namespace App\EventSubscriber;

use App\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use App\Service\FileUploader;

class ImageUploadSubscriber implements EventSubscriberInterface
{
    private $dir;
    private $fileUploader;

    public function __construct($dir, FileUploader $fileUploader)
    {
        $this->dir = $dir;
        $this->fileUploader = $fileUploader;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            Events::IMAGE_UPLOADER => 'onUploaded',
        ];
    }

    public function onUploaded(GenericEvent $event): void
    {
        $trick = $event->getSubject();

        foreach ($trick->getImages()->getValues() as $image) {
            $file = $image->getFile();
            if ($file !== null) {
                $this->fileUploader->setTargetDirectory($this->dir);
                $fileName = $this->fileUploader->genFileName($file);
                $this->fileUploader->upload($file);
                $image->setName($fileName);
            }
        }
    }
}
