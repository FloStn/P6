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
use App\Handler\Form\User\RegisterFormHandler;
use App\Handler\Form\User\ForgotPasswordFormHandler;
use App\Handler\Form\User\ResetPasswordFormHandler;

class UserController extends Controller
{
    /**
     * @Route("/register", name="user_register")
     */
    public function register(Request $request, RegisterFormHandler $handler)
    {
        $user = new User();
        $userForm = $this->createForm(UserType::class, $user)->handleRequest($request);

        if ($handler->handle($user, $userForm))
        {
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
    public function forgotPassword(Request $request, ForgotPasswordFormHandler $handler)
    {
        $user = new User();
        $userForm = $this->createForm(ForgotPasswordType::class, $user)->handleRequest($request);
        
        if ($handler->handle($user, $userForm))
        {
            return $this->redirectToRoute('tricks_index');
        }
        
        return $this->render('user/forgot_password.html.twig', array(
            'userForm' => $userForm->createView()
        ));
    }

    /**
     * @Route("/reset-password/{token}", name="user_reset_password")
     */
    public function resetPassword(Request $request, UserRepository $userRepository, $token, ResetFormHandler $handler)
    {
        $user = $userRepository->findOneBy(['token' => $token]);
        if ($user == null)
        {
            return $this->redirectToRoute('tricks_index');
        }
        $userForm = $this->createForm(ResetPasswordType::class, $user)->handleRequest($request);

        if ($handler->handle($user, $userForm))
        {
            return $this->redirectToRoute('tricks_index');
        }

        return $this->render('user/reset_password.html.twig', array(
            'user_form' => $userForm->createView()
        ));
    }
}
