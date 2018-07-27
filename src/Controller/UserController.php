<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\User;
use App\Entity\Avatar;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\UserType;
use App\Form\ResetPasswordType;
use App\Form\ForgotPasswordType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Service\TokenGen;
use App\Repository\UserRepository;
use App\Service\Email;

class UserController extends Controller
{
    /**
     * @Route("/register", name="user_register")
     */
    public function register(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder, TokenGen $tokenGen, Email $email)
    {
        $user = new User();
        $userForm = $this->createForm(UserType::class, $user);

        $userForm->handleRequest($request);
        if ($userForm->isSubmitted() && $userForm->isValid())
        {
            $password = $passwordEncoder->encodePassword($user, $user->getRawPassword());
            $user->setPassword($password);
            $token = $tokenGen->newToken();
            $user->setToken($token);
            
            $em->persist($user);
            $em->flush();

            $title = 'Validation de votre compte SnowTricks';
            $template = 'base.html.twig';
            $email->send($user, $title, $template);

            return $this->redirectToRoute('tricks_index');
        }

        return $this->render('user/register.html.twig', array(
            'user_form' => $userForm->createView()
        ));
    }

    /**
     * @Route("/activate/{token}", name="user_activate")
     */
    public function activate(Request $request, EntityManagerInterface $em, UserRepository $userRepository)
    {
        $user = $userRepository->findOneBy(['token' => $request->attributes->get('token')]);
        if ($user == null)
        {
            return $this->redirectToRoute('tricks_index');
        } else {
            $user->setIsActive(1);
            $user->setToken('');

            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('tricks_index');
        }
    }

    /**
     * @Route("/forgot-password", name="user_forgot_password")
     */
    public function forgotPassword(Request $request, EntityManagerInterface $em, Email $email, UserRepository $userRepository, TokenGen $tokenGen)
    {
        $user = new User();
        $userForm = $this->createForm(UserType::class, $user);

        $userForm->handleRequest($request);
        if ($userForm->isSubmitted() && $userForm->isValid())
        {
            $user = $userRepository->findOneBy(['username' => $user->getUsername()]);
            $token = $tokenGen->newToken();
            $user->setToken($token);

            $em->persist($user);
            $em->flush();

            $title = 'Réinitialisation de votre mot de passe SnowTricks';
            $template = 'forgot_password.html.twig';
            $email->send($user, $title, $template);

            return $this->redirectToRoute('tricks_index');
        }
        
        return $this->render('user/forgot_password.html.twig', array(
            'user_form' => $userForm->createView()
        ));
    }

    /**
     * @Route("/reset-password/{token}", name="user_reset_password")
     */
    public function resetPassword(Request $request, EntityManagerInterface $em, UserRepository $userRepository, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = $userRepository->findOneBy(['token' => $request->attributes->get('token')]);
        if ($user == null)
        {
            return $this->redirectToRoute('tricks_index');
        }
        $userForm = $this->createForm(ResetPasswordType::class, $user);

        $userForm->handleRequest($request);
        if ($userForm->isSubmitted() && $userForm->isValid())
        {
            $password = $passwordEncoder->encodePassword($user, $user->getRawPassword());
            $user->setPassword($password);
            $user->setToken('');

            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('tricks_index');
        }
        return $this->render('user/reset_password.html.twig', array(
            'user_form' => $userForm->createView()
        ));
    }
}
