<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\Comment;

class CommentController extends Controller
{
    public function getCommentsByTrick($slug)
    {
        $showComments = 2;
        $start = 0;
        $show = 2;
        $limit = $show*$showComments;
        $showMore = $show++;

        $em = $this->getDoctrine()->getManager();
        $commentRepository = $em->getRepository(Comment::class);
        $countAllComments = $commentRepository->countAll();
        $totalPages = ceil($countAllComments/$limit);
        $comments = $commentRepository->getPagination($start, $limit);

        return $this->render(
            'comment/comments.html.twig',
            array('comments' => $comments
            ));
    }
}
