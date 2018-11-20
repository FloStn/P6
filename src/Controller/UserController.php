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
use App\Service\TokenGen;
use App\Repository\UserRepository;
use App\Service\Email;
use App\Handler\Form\User\RegisterHandler;
use App\Handler\Form\User\ForgotPasswordHandler;
use App\Handler\Form\User\ResetPasswordHandler;

class UserController extends Controller
{
    /**
     * @Route("/register", name="user_register")
     */
    public function register(Request $request, RegisterHandler $handler)
    {
        $user = new User();
        $userForm = $this->createForm(UserType::class, $user)->handleRequest($request);

        if ($handler->handle($user, $userForm))
        {
            $request->getSession()->getFlashBag()->add('success', 'Votre compte a été créé ! Veuillez vous rendre sur le lien envoyé sur votre email afin de l\'activer.');
            return $this->redirectToRoute('tricks_index', array('page' => 1));
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
            $request->getSession()->getFlashBag()->add('warning', 'Impossible de trouver l\'utilisateur !');
            return $this->redirectToRoute('tricks_index', array('page' => 1));
        } else {
            $user->setIsActive(1);
            $user->setToken('');

            $em->persist($user);
            $em->flush();

            $request->getSession()->getFlashBag()->add('warning', 'Votre compte a été activé ! Bienvenue parmis nous !');
            return $this->redirectToRoute('tricks_index', array('page' => 1));
        }
    }

    /**
     * @Route("/forgot-password", name="user_forgot_password")
     */
    public function forgotPassword(Request $request, ForgotPasswordHandler $handler)
    {
        $user = new User();
        $userForm = $this->createForm(ForgotPasswordType::class, $user)->handleRequest($request);
        
        if ($handler->handle($user, $userForm))
        {
            $request->getSession()->getFlashBag()->add('success', 'Un lien de réinitialisation a été envoyé sur votre email !');
            return $this->redirectToRoute('tricks_index', array('page' => 1));
        }
        
        return $this->render('user/forgot_password.html.twig', array(
            'userForm' => $userForm->createView()
        ));
    }

    /**
     * @Route("/reset-password/{token}", name="user_reset_password")
     */
    public function resetPassword(Request $request, UserRepository $userRepository, $token, ResetPasswordHandler $handler)
    {
        $user = $userRepository->findOneBy(['token' => $token]);
        if ($user == null)
        {
            $request->getSession()->getFlashBag()->add('warning', 'Impossible de trouver l\'utilisateur !');
            return $this->redirectToRoute('tricks_index', array('page' => 1));
        }
        $resetPasswordForm = $this->createForm(ResetPasswordType::class, $user)->handleRequest($request);

        if ($handler->handle($user, $resetPasswordForm))
        {
            $request->getSession()->getFlashBag()->add('success', 'Votre mot de passe a bien été modifié !');
            return $this->redirectToRoute('tricks_index', array('page' => 1));
        }

        return $this->render('user/reset_password.html.twig', array(
            'resetPasswordForm' => $resetPasswordForm->createView()
        ));
    }
}
