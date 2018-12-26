<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Store;

class DefaultController extends AbstractController
{
    /**
      * @Route("/")
      */
    public function index()
    {
        $stores = $this->getDoctrine()
        ->getRepository(Store::class)
        ->findAll();

        if (!$stores) {
            throw $this->createNotFoundException(
                'No store found'
            );
        }

        return $this->render('index.html.twig', array('data' => $stores));
    }
}