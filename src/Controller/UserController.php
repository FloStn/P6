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
            return $this->redirectToRoute('tricks_index', array('page' => 1));
        } else {
            $user->setIsActive(1);
            $user->setToken('');

            $em->persist($user);
            $em->flush();

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
            return $this->redirectToRoute('tricks_index', array('page' => 1));
        }
        $resetPasswordForm = $this->createForm(ResetPasswordType::class, $user)->handleRequest($request);

        if ($handler->handle($user, $resetPasswordForm))
        {
            return $this->redirectToRoute('tricks_index', array('page' => 1));
        }

        return $this->render('user/reset_password.html.twig', array(
            'resetPasswordForm' => $resetPasswordForm->createView()
        ));
    }
}
