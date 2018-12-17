<?php

namespace App\Handler\Form\User;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ResetPasswordHandler
{
    private $entityManager;
    private $passwordEncoder;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function handle($user, $resetPasswordForm)
    {
        if ($resetPasswordForm->isSubmitted() && $resetPasswordForm->isValid()) {
            $password = $this->passwordEncoder->encodePassword($user, $user->getRawPassword());
            $user->setPassword($password);
            $user->setToken('');

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            return true;
        }
    }
}
