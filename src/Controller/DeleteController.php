<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Store;

class DeleteController extends AbstractController
{
    /**
      * @Route("/delete/{id}")
      */
    public function index($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $store = $this->getDoctrine()
        ->getRepository(Store::class)
        ->find($id);
        $entityManager->remove($store);
        $entityManager->flush();
        return $this->redirectToRoute('app_default_index');
    }
}