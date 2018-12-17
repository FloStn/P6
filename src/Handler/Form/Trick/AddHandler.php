<?php

namespace App\Handler\Form\Trick;

use Doctrine\ORM\EntityManagerInterface;
use App\Events;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class AddHandler
{
    private $entityManager;
    private $eventDispatcher;

    public function __construct(EntityManagerInterface $entityManagerInterface, EventDispatcherInterface $eventDispatcher)
    {
        $this->entityManager = $entityManagerInterface;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function handle($trick, $trickForm, $user)
    {
        if ($trickForm->isSubmitted() && $trickForm->isValid()) {
            $trickData = $trickForm->getData();
            $slug = $trick->newSlug($trickData->getName());

            $trick->setAuthor($user);
            $trick->setSlug($slug);

            $event = new GenericEvent($trick);
            $this->eventDispatcher->dispatch(Events::IMAGE_FORWARD_UPLOADER, $event);
            $this->eventDispatcher->dispatch(Events::IMAGE_UPLOADER, $event);

            $this->entityManager->persist($trick);
            $this->entityManager->flush();

            return true;
        }
    }
}
