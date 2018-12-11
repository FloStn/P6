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
use App\Handler\Form\Trick\DetailsHandler;
use App\Handler\Form\Trick\EditHandler;
use App\Handler\Form\Trick\AddHandler;
use Symfony\Component\Filesystem\Filesystem;
use App\Service\Pagination;
use App\Service\FileUploader;


class TrickController extends Controller
{
    /**
     * @Route("/home/{page}", methods={"GET"}, name="tricks_index")
     *
     * @param Request $request
     * 
     * @param TrickRepository $trickRepository
     * 
     * @param EntityManagerInterface $em
     *
     * @return array
     */
    public function index(Request $request, $page, TrickRepository $trickRepository, EntityManagerInterface $em, Pagination $pagination)
    {
        $tricksPerPage = 15;
        $allTricks = $trickRepository->findAll();
        $totalPages = ceil(count($allTricks) / $tricksPerPage);
        if ($page < 1 || $page == 0 || $page > $totalPages) {
            $page = 1;
        }
        $tricks = $trickRepository->getPagination(0, $tricksPerPage * $page);

        return $this->render('tricks.html.twig', array(
            'tricks' => $tricks,
            'page' => $page,
            'totalPages' => $totalPages
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
    public function details(Request $request, TrickRepository $trickRepository, CommentRepository $commentRepository, $slug, $page, Pagination $pagination, DetailsHandler $handler)
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
            $request->getSession()->getFlashBag()->add('success', 'Votre commentaire a bien été publié !');
            return $this->redirectToRoute('trick_details', array('slug' => $slug, 'page' => $page));
        }

        return $this->render('trick.html.twig', array(
            'trick' => $trick,
            'page' => $page,
            'comments' => $comments,
            'totalPages' => $pagination->getTotalPages(),
            'commentForm' => $commentForm->createView()
        ));
    }

    /**
     * @Route("/edit/{slug}", methods={"GET", "POST"}, name="trick_edit")
     * 
     * @param Request $request
     * 
     
     */
    public function edit(Request $request, TrickRepository $trickRepository, $slug, EditHandler $handler)
    {
        $trick = $trickRepository->findOneBy(['slug' => $slug]);
        $trickForm = $this->createForm(TrickType::class, $trick)->handleRequest($request);

        if ($handler->handle($trick, $trickForm))
        {
            $request->getSession()->getFlashBag()->add('success', 'La figure a bien été modifiée !');
            return $this->redirectToRoute('trick_details', array('slug' => $slug, 'page' => 1));
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
    public function add(Request $request, TrickRepository $trickRepository, AddHandler $handler)
    {
        $trick = new Trick();
        $trickForm = $this->createForm(TrickType::class, $trick)->handleRequest($request);
        $user = $this->getUser();

        if ($handler->handle($trick, $trickForm, $user))
        {
            $request->getSession()->getFlashBag()->add('success', 'La figure a bien été ajoutée !');
            return $this->redirectToRoute('tricks_index', array('page' => 1));
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

        $request->getSession()->getFlashBag()->add('success', 'La figure a bien été supprimée !');
        return $this->redirectToRoute('tricks_index', array('page' => 1));
    }
}
