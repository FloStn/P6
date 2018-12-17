<?php

namespace App\Handler\Form\User;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Service\TokenGen;
use App\Service\Email;

class RegisterHandler
{
    private $entityManager;
    private $passwordEncoder;
    private $tokenGen;
    private $email;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder, TokenGen $tokenGen, Email $email)
    {
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->tokenGen = $tokenGen;
        $this->email = $email;
    }

    public function handle($user, $userForm)
    {
        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $password = $this->passwordEncoder->encodePassword($user, $user->getRawPassword());
            $user->setPassword($password);
            $token = $this->tokenGen->newToken();
            $user->setToken($token);

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $title = 'Validation de votre compte SnowTricks';
            $template = 'base.html.twig';
            $this->email->send($user, $title, $template);

            return true;
        }
    }
}
