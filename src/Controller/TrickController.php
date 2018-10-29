<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Entity\Trick;
use App\Entity\Comment;
use App\Entity\TrickGroup;
use App\Entity\ImageForward;
use App\Form\TrickType;
use App\Form\VideoType;
use App\Form\CommentType;
use App\Repository\TrickRepository;
use App\Repository\TrickGroupRepository;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Doctrine\Common\Collections\ArrayCollection;
use App\Handler\Form\Trick\DetailsFormHandler;
use App\Handler\Form\Trick\EditFormHandler;
use App\Handler\Form\Trick\AddFormHandler;
use Symfony\Component\Filesystem\Filesystem;
use App\Service\Pagination;
use App\Service\FileUploader;


class TrickController extends Controller
{
    /**
     * Liste l'ensemble des figures triées par date de publication.
     *
     * @Route("/home", methods={"GET"}, name="tricks_index")
     *
     * @param Request $request
     * 
     * @param TrickRepository $trickRepository
     * 
     * @param EntityManagerInterface $em
     *
     * @return array
     */
    public function index(Request $request, TrickRepository $trickRepository, EntityManagerInterface $em)
    {
        $perPage = 8;
        $start = 0;
        $page = $request->query->get('page_tricks', 1);
        $limit = $perPage*$page;
        $showMore = $page++;

        $countAllTricks = $trickRepository->countAllTricks();
        $totalPages = ceil($countAllTricks/$limit);
        
        $tricks = $trickRepository->getPagination($start, $limit);

        return $this->render('tricks.html.twig', array(
            'tricks' => $tricks,
            'showMore' => $showMore
        ));
    }


    /**
     * @Route("/details/{slug}/{page}", methods={"GET", "POST"}, name="trick_details")
     * 
     * @param Request $request
     * 
     * @param TrickRepository $trickRepository
     * 
     * @param CommentRepository $commentRepository
     * 
     * @param EntityManagerInterface $em
     *
     * @return array
     */
    public function details(Request $request, TrickRepository $trickRepository, CommentRepository $commentRepository, $slug, $page, Pagination $pagination, DetailsFormHandler $handler)
    {
        $trick = $trickRepository->findOneBy(['slug' => $slug]);
        $allComments = $commentRepository->findBy(['trick' => $trick]);
        $pagination->init($page, $allComments);
        $comments = $commentRepository->getPagination($trick->getId(), $pagination->getStart(), $pagination->getLimit());

        $comment = new Comment();
        $user = $this->getUser();
        $commentForm = $this->createForm(CommentType::class, $comment)->handleRequest($request);


        if ($handler->handle($trick, $comment, $commentForm, $user))
        {
            return $this->redirectToRoute('trick_details', array('slug' => $slug, 'page' => $page));
        }

        return $this->render('trick.html.twig', array(
            'trick' => $trick,
            'page' => $page,
            'comments' => $comments,
            'nbPages' => $pagination->getNbPages(),
            'commentForm' => $commentForm->createView()
        ));
    }

    /**
     * @Route("/edit/{slug}", methods={"GET", "POST"}, name="trick_edit")
     * 
     * @param Request $request
     * 
     
     */
    public function edit(Request $request, TrickRepository $trickRepository, $slug, EditFormHandler $handler)
    {
        $trick = $trickRepository->findOneBy(['slug' => $slug]);
        $trickForm = $this->createForm(TrickType::class, $trick)->handleRequest($request);

        if ($handler->handle($trick, $trickForm))
        {
            return $this->redirectToRoute('tricks_index');
        }
        
        return $this->render('trick/edit.html.twig', array(
            'trick' => $trick,
            'trickForm' => $trickForm->createView()
        ));
    }

    /**
     * @Route("/add", methods={"GET", "POST"}, name="trick_add")
     * 
     * @param Request $request
     *
    */
    public function add(Request $request, TrickRepository $trickRepository, AddFormHandler $handler)
    {
        $trick = new Trick();
        $trickForm = $this->createForm(TrickType::class, $trick)->handleRequest($request);
        $user = $this->getUser();

        if ($handler->handle($trick, $trickForm, $user))
        {
            return $this->redirectToRoute('tricks_index');
        }

        return $this->render('trick/add.html.twig', array(
            'trick' => $trick,
            'trickForm' => $trickForm->createView()
        ));
    }

    /**
     * @Route("/delete/{slug}", methods={"GET", "POST"}, name="trick_delete")
     * 
     * @param Request $request
     * 
     */
    public function delete(Request $request, TrickRepository $trickRepository, EntityManagerInterface $em, $slug)
    {
        $slug = $request->attributes->get('slug');
        $trick = $trickRepository->findOneBy(['slug' => $slug]);

        $em->remove($trick);
        $em->flush();

        return $this->redirectToRoute('tricks_index');
    }
}
