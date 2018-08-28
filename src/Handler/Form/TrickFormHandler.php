<?php

namespace App\Handler\Form;

use Symfony\Component\Form\FormInterface;
use Doctrine\ORM\EntityManagerInterface;


class TrickFormHandler
{
    private $entityManager;

    public function __construct(EntityManagerInterface $em)
    {
        $this->entityManager = $em;
    }

    public function handle(FormInterface $trickForm, $trick)
    {
        if ($trickForm->isSubmitted() && $trickForm->isValid()) {
            // get rid of the ones that the user got rid of in the interface (DOM)

            $this->entityManager->persist($trick);
            $this->entityManager->flush();
            return true;
          }
    }
}