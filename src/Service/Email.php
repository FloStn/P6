<?php

namespace App\Service;

use App\Entity\User;

class Email
{
    private $mailer;
    private $twig;

    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    public function send(User $user, $title, $template)
    {
        $from = 'flo.stein9578@gmail.com';
        $to = $user->getEmail();

        $message = (new \Swift_Message($title))
                ->setFrom($from)
                ->setTo($to)
                ->setBody(
                    $this->twig->render(
                        'email/'.$template, [
                            'user' => $user,
                        ]
                ),
                    'text/html'
                );

        $this->mailer->send($message);
    }
}
