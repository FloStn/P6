<?php

namespace App\Handler\Form\User;

use Doctrine\ORM\EntityManagerInterface;
use App\Service\TokenGen;
use App\Repository\UserRepository;
use App\Service\Email;

class ForgotPasswordHandler
{
    private $entityManager;
    private $tokenGen;
    private $email;
    private $userRepository;

    public function __construct(EntityManagerInterface $entityManager, TokenGen $tokenGen, Email $email, UserRepository $userRepository)
    {
        $this->entityManager = $entityManager;
        $this->tokenGen = $tokenGen;
        $this->email = $email;
        $this->userRepository = $userRepository;
    }

    public function handle($user, $userForm)
    {
        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $user = $this->userRepository->findOneBy(['username' => $user->getUsername()]);
            $token = $this->tokenGen->newToken();
            $user->setToken($token);

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $title = 'Réinitialisation de votre mot de passe SnowTricks';
            $template = 'forgot_password.html.twig';
            $this->email->send($user, $title, $template);

            return true;
        }
    }
}
