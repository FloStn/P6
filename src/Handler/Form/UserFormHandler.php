<?php

namespace App\Handler\Form;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Service\TokenGen;
use App\Repository\UserRepository;
use App\Service\Email;

class UserFormHandler
{
    private $entityManager;
    private $email;
    private $passwordEncoder;
    private $tokenGen;

    public function __construct(EntityManagerInterface $em, Email $email, UserPasswordEncoderInterface $passwordEncoder, TokenGen $tokenGen)
    {
        $this->entityManager = $em;
        $this->email = $email;
        $this->passwordEncoder = $passwordEncoder;
        $this->tokenGen = $tokenGen;
    }

    public function registerHandle($user, $userForm)
    {
        if ($userForm->isSubmitted() && $userForm->isValid())
        {
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

    public function forgotPasswordHandle($user, $userForm, UserRepository $userRepository)
    {
        if ($userForm->isSubmitted() && $userForm->isValid())
        {
            $user = $userRepository->findOneBy(['username' => $user->getUsername()]);
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

    public function resetPasswordHandle($user, $userForm)
    {
        if ($userForm->isSubmitted() && $userForm->isValid())
        {
            $password = $this->passwordEncoder->encodePassword($user, $user->getRawPassword());
            $user->setPassword($password);
            $user->setToken('');

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            return true;
        }
    }
}