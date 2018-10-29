<?php

namespace App\Handler\Form\Trick;

use Doctrine\ORM\EntityManagerInterface;

class DetailsFormHandler
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManagerInterface)
    {
        $this->entityManager = $entityManagerInterface;
    }

    public function handle($trick, $comment, $commentForm, $user)
    {
        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $comment->setTrick($trick);
            $comment->setAuthor($user);

            $this->entityManager->persist($comment);
            $this->entityManager->flush();

           return true;
        }
    }
}