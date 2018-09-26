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
use App\Handler\Form\TrickFormHandler;
use Symfony\Component\Filesystem\Filesystem;


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
    public function details(Request $request, TrickRepository $trickRepository, CommentRepository $commentRepository, EntityManagerInterface $em, $slug, $page)
    {
        $trick = $trickRepository->findOneBy(['slug' => $slug]);
        $perPage = 3;
        $allComments = $commentRepository->findBy(['trick' => $trick]) ;
        $nbPages = ceil(count($allComments) / $perPage);
        
        if ($page < 1 || $page > $nbPages) {
            $page = 1;
        }

        $start = $perPage * $page - $perPage;
        $limit = $perPage;
        
        $comments = $commentRepository->getPagination($trick->getId(), $start, $limit);
        $comment = new Comment();
        $commentForm = $this->createForm(CommentType::class, $comment)->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $user = $this->getUser();
            
            $comment->setTrick($trick);
            $comment->setAuthor($user);

            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('trick_details', array('slug' => $slug, 'page' => $page));
        }

        return $this->render('trick.html.twig', array(
            'trick' => $trick,
            'page' => $page,
            'comments' => $comments,
            'nbPages' => $nbPages,
            'commentForm' => $commentForm->createView()
        ));
    }

    /**
     * @Route("/edit/{slug}", methods={"GET", "POST"}, name="trick_edit")
     * 
     * @param Request $request
     * 
     
     */
    public function edit(Request $request, TrickRepository $trickRepository, TrickGroupRepository $trickGroupRepository, EntityManagerInterface $em, $slug)
    {
        $trick = $trickRepository->findOneBy(['slug' => $slug]);
        $trickGroups = $trickGroupRepository->findAll();

        $trickForm = $this->createForm(TrickType::class, $trick)->handleRequest($request);
    
            if ($trickForm->isSubmitted() && $trickForm->isValid()) {

                $trickData = $trickForm->getData();
                //dump($trickData);
                //die;
                $file = $trickData->getImageForward()->getFile();
                
                if ($file !== null) {
                    
                    $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();
    
                    $file->move($this->getParameter('upload_directory'), $fileName);
    
                    $imageForward = $trick->getImageForward();

                    $fileSystem = new Filesystem();
                    $fileSystem->remove($this->getParameter('upload_directory').'/'.$imageForward->getFileName());
                
                    $imageForward->setFileName($fileName);
                }

                foreach ($trickData->getImages()->getValues() as $image)
                {
                    $file = $image->getFile();
                    //dump($image->getFile());
                    if ($file !== null) {
                        $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();
    
                        $file->move($this->getParameter('upload_directory'), $fileName);
    
                        $image->setName($fileName);
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
     * @return string
     */
    private function generateUniqueFileName()
    {
        return md5(uniqid());
    }

    /**
     * @Route("/add", methods={"GET", "POST"}, name="trick_add")
     * 
     * @param Request $request
     *
    */
    public function add(Request $request, TrickRepository $trickRepository, EntityManagerInterface $em)
    {
        $trick = new Trick();
        $trickForm = $this->createForm(TrickType::class, $trick);
        
        $trickForm->handleRequest($request);
        if ($trickForm->isSubmitted() && $trickForm->isValid()) {
            $trickData = $trickForm->getData();
            $user = $this->getUser();
            $slug = $trick->newSlug($trickData->getName());
            
            $trick->setAuthor($user);
            $trick->setSlug($slug);
            
            $fileImageForwardData = $trickData->getImageForward()->getFile();
            if ($fileImageForwardData !== null)
            {
                $fileImageForwardDataName = $this->generateUniqueFileName().'.'.$fileImageForwardData->guessExtension();
                $fileImageForwardData->move($this->getParameter('trick_image_forward_upload_directory'), $fileImageForwardDataName);

                $trick->getImageForward()->setFileName($fileImageForwardDataName);
            }

            foreach ($trickData->getImages()->getValues() as $image)
                {
                    $file = $image->getFile();
                    //dump($file);
                    //die;
                    if ($file !== null) {
                        $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();
    
                        $file->move($this->getParameter('trick_image_upload_directory'), $fileName);
    
                        $image->setName($fileName);
                    }
                }
            
            $em->persist($trick);
            $em->flush();

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
