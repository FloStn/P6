<?php

namespace App\Handler\Form\Trick;

use Doctrine\ORM\EntityManagerInterface;
use App\Events;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class EditFormHandler
{
    private $entityManager;
    private $eventDispatcher;

    public function __construct(EntityManagerInterface $entityManagerInterface, EventDispatcherInterface $eventDispatcher)
    {
        $this->entityManager = $entityManagerInterface;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function handle($trick, $trickForm)
    {
        if ($trickForm->isSubmitted() && $trickForm->isValid()) {
            //On déclenche l'event
            $event = new GenericEvent($trick);
            $this->eventDispatcher->dispatch(Events::IMAGE_FORWARD_UPLOADER, $event);
            
            //$eventImage = new GenericEvent($trick);
            $this->eventDispatcher->dispatch(Events::IMAGE_UPLOADER, $event);
            //$this->uploadHandle($trick);
            
            $this->entityManager->persist($trick);
            $this->entityManager->flush();

            return true;
          }
    }
}