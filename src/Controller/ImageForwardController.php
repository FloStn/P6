<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ImageForwardRepository;
use Doctrine\ORM\EntityManagerInterface;

class ImageForwardController extends AbstractController
{
    /**
     * @Route("/image-forward-delete/{id}/{slug}", methods={"GET"}, name="image_forward_delete")
     *
     * @param Request                $request
     * @param ImageForwardRepository $imageForwardRepository
     * @param EntityManagerInterface $em
     * @param $id
     * @param $slug
     *
     * @return Symfony\Bundle\FrameworkBundle\Controller\ControllerTrait
     */
    public function delete(
        Request $request,
        ImageForwardRepository $imageForwardRepository,
        EntityManagerInterface $em,
        $id,
        $slug)
    {
        $imageForward = $imageForwardRepository->find($id);
        $em->remove($imageForward);
        $em->flush();

        return $this->redirectToRoute('trick_edit', ['slug' => $slug]);
    }
}
