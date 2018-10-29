<?php

namespace App\Handler\Form\Trick;

use Doctrine\ORM\EntityManagerInterface;

class AddFormHandler
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManagerInterface)
    {
        $this->entityManager = $entityManagerInterface;
    }

    public function handle($trick, $trickForm, $user)
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
}