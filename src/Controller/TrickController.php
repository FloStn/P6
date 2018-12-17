<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Trick;
use App\Entity\Comment;
use App\Form\TrickType;
use App\Form\CommentType;
use App\Repository\TrickRepository;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Handler\Form\Trick\DetailsHandler;
use App\Handler\Form\Trick\EditHandler;
use App\Handler\Form\Trick\AddHandler;
use App\Service\Pagination;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class TrickController extends Controller
{
    /**
     * @Route("/home/{page}", methods={"GET"}, name="tricks_index")
     *
     * @param Request         $request
     * @param TrickRepository $trickRepository
     * @param Pagination      $pagination
     *
     * @return Symfony\Component\Form\FormRendererEngineInterface
     */
    public function index(
        Request $request,
        $page,
        TrickRepository $trickRepository,
        Pagination $pagination)
    {
        $tricksPerPage = 15;
        $allTricks = $trickRepository->findAll();
        $totalPages = ceil(count($allTricks) / $tricksPerPage);
        if ($page < 1 || 0 == $page || $page > $totalPages) {
            $page = 1;
        }
        $tricks = $trickRepository->getPagination(0, $tricksPerPage * $page);

        return $this->render('trick/tricks.html.twig', [
            'tricks' => $tricks,
            'page' => $page,
            'totalPages' => $totalPages,
        ]);
    }

    /**
     * @Route("/details/{slug}/{page}", methods={"GET", "POST"}, name="trick_details")
     *
     * @param Request           $request
     * @param TrickRepository   $trickRepository
     * @param CommentRepository $commentRepository
     * @param $slug
     * @param $page
     * @param Pagination     $pagination
     * @param DetailsHandler $handler
     *
     * @return Symfony\Component\Form\FormRendererEngineInterface
     */
    public function details(
        Request $request,
        TrickRepository $trickRepository,
        CommentRepository $commentRepository,
        $slug,
        $page,
        Pagination $pagination,
        DetailsHandler $handler)
    {
        $trick = $trickRepository->findOneBy(['slug' => $slug]);
        $allComments = $commentRepository->findBy(['trick' => $trick]);
        $pagination->init($page, $allComments);
        $comments = $commentRepository->getPagination($trick->getId(), $pagination->getStart(), $pagination->getLimit());

        $comment = new Comment();
        $user = $this->getUser();
        $commentForm = $this->createForm(CommentType::class, $comment)->handleRequest($request);

        if ($handler->handle($trick, $comment, $commentForm, $user)) {
            $request->getSession()->getFlashBag()->add('success', 'Votre commentaire a bien été publié !');

            return $this->redirectToRoute('trick_details', ['slug' => $slug, 'page' => $page]);
        }

        return $this->render('trick/trick.html.twig', [
            'trick' => $trick,
            'page' => $page,
            'comments' => $comments,
            'totalPages' => $pagination->getTotalPages(),
            'commentForm' => $commentForm->createView(),
        ]);
    }

    /**
     * @Route("/edit/{slug}", methods={"GET", "POST"}, name="trick_edit")
     * @IsGranted("ROLE_USER")
     *
     * @param Request         $request
     * @param TrickRepository $trickRepository
     * @param $slug
     * @param EditHandler $handler
     *
     * @return Symfony\Component\Form\FormRendererEngineInterface
     */
    public function edit(
        Request $request,
        TrickRepository $trickRepository,
        $slug,
        EditHandler $handler)
    {
        $trick = $trickRepository->findOneBy(['slug' => $slug]);
        $trickForm = $this->createForm(TrickType::class, $trick)->handleRequest($request);

        if ($handler->handle($trick, $trickForm)) {
            $request->getSession()->getFlashBag()->add('success', 'La figure a bien été modifiée !');

            return $this->redirectToRoute('trick_details', ['slug' => $slug, 'page' => 1]);
        }

        return $this->render('trick/edit.html.twig', [
            'trick' => $trick,
            'trickForm' => $trickForm->createView(),
        ]);
    }

    /**
     * @Route("/add", methods={"GET", "POST"}, name="trick_add")
     * @IsGranted("ROLE_USER")
     *
     * @param Request         $request
     * @param TrickRepository $trickRepository
     * @param AddHandler      $handler
     *
     * @return Symfony\Component\Form\FormRendererEngineInterface
     */
    public function add(
        Request $request,
        TrickRepository $trickRepository,
        AddHandler $handler)
    {
        $trick = new Trick();
        $trickForm = $this->createForm(TrickType::class, $trick)->handleRequest($request);
        $user = $this->getUser();

        if ($handler->handle($trick, $trickForm, $user)) {
            $request->getSession()->getFlashBag()->add('success', 'La figure a bien été ajoutée !');

            return $this->redirectToRoute('tricks_index', ['page' => 1]);
        }

        return $this->render('trick/add.html.twig', [
            'trick' => $trick,
            'trickForm' => $trickForm->createView(),
        ]);
    }

    /**
     * @Route("/delete/{slug}", methods={"GET", "POST"}, name="trick_delete")
     * @IsGranted("ROLE_USER")
     *
     * @param Request                $request
     * @param TrickRepository        $trickRepository
     * @param EntityManagerInterface $em
     * @param $slug
     *
     * @return Symfony\Bundle\FrameworkBundle\Controller\ControllerTrait
     */
    public function delete(
        Request $request,
        TrickRepository $trickRepository,
        EntityManagerInterface $em,
        $slug)
    {
        $slug = $request->attributes->get('slug');
        $trick = $trickRepository->findOneBy(['slug' => $slug]);

        $em->remove($trick);
        $em->flush();

        $request->getSession()->getFlashBag()->add('success', 'La figure a bien été supprimée !');

        return $this->redirectToRoute('tricks_index', ['page' => 1]);
    }
}
