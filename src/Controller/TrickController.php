<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\Trick;
use App\Entity\Comment;
use App\Entity\TrickGroup;
use Symfony\Component\HttpFoundation\Request;
use App\Form\TrickType;
use App\Form\VideoType;
use App\Repository\TrickRepository;
use App\Repository\TrickGroupRepository;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Doctrine\Common\Collections\ArrayCollection;
use App\Handler\Form\TrickFormHandler;

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
            'page' => $page
        ));
    }


    /**
     * @Route("/details/{slug}", methods={"GET"}, name="trick_details")
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
    public function details(Request $request, TrickRepository $trickRepository, CommentRepository $commentRepository, EntityManagerInterface $em)
    {
        $perPage = 1;
        $page = $request->query->get('page_comments', 1);
        $limit = $perPage*$page;
        
        $trick = $trickRepository->findOneBy(['slug' => $request->attributes->get('slug')]);
        $comments = $commentRepository->getPagination($trick->getId(), $limit);
        //$nbPages = ceil(count($comments) / $perPage);

        return $this->render('trick.html.twig', array(
            'trick' => $trick,
            'page' => $request->attributes->get('page'),
            'comments' => $comments
        ));
    }

    /**
     * @Route("/edit/{slug}", methods={"GET", "POST"}, name="trick_edit")
     * 
     * @param Request $request
     * 
     
     */
    public function edit(Request $request, TrickRepository $trickRepository, TrickGroupRepository $trickGroupRepository, EntityManagerInterface $em)
    {
        $slug = $request->attributes->get('slug');
        $trick = $trickRepository->findOneBy(['slug' => $slug]);
        $trickGroups = $trickGroupRepository->findAll();

        // save the records that are in the database first to compare them with the new one the user sent
        // make sure this line comes before the $form->handleRequest();
        $orignalExp = new ArrayCollection();
        foreach ($trick->getVideos() as $video) {
            $orignalExp->add($video);
        }

        $trickForm = $this->createForm(TrickType::class, $trick)->handleRequest($request);

        if ($trickForm->isSubmitted() && $trickForm->isValid()) {
            // get rid of the ones that the user got rid of in the interface (DOM)
            foreach ($orignalExp as $video) {
                // check if the exp is in the $user->getExp()
//                dump($user->getExp()->contains($exp));
                if ($trick->getVideos()->contains($video) === false) {
                    $em->remove($video);
                }
            }
            $em->persist($trick);
            $em->flush();

        
            return $this->redirectToRoute('trick_edit', array('trick' => $trick, 'slug' => $slug));
        }
        

        return $this->render('trick/edit.html.twig', array(
            'trick' => $trick,
            'trickGroups' => $trickGroups,
            'trickForm' => $trickForm->createView()
        ));
    }

    /**
     * @Route("/add", methods={"GET", "POST"}, name="trick_add")
     * 
     * @param Request $request
     * 
     * @Security("is_granted('ROLE_USER')")
    */
    public function add(Request $request, TrickRepository $trickRepository, TrickGroupRepository $trickGroupRepository, EntityManagerInterface $em)
    {
        $trick = new Trick();
        $trickGroups = $trickGroupRepository->findAll();
        $trickForm = $this->createForm(TrickType::class, $trick);
        
        $trickForm->handleRequest($request);
        if ($trickForm->isSubmitted() && $trickForm->isValid()) {
            $user = $this->getUser();
            $slug = $trick->newSlug($trick->getName());
            
            $trick->setAuthor($user);
            $trick->setSlug($slug);
            

            $em->persist($trick);
            $em->flush($trick);
            return $this->redirectToRoute('tricks_index');
        }

        return $this->render('trick/add.html.twig', array(
            'trick' => $trick,
            'trickGroups' => $trickGroups,
            'trickForm' => $trickForm->createView()
        ));
    }
}
