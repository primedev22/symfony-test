<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Entity\Store;

class AddStoreController extends AbstractController
{
    /**
      * @Route("/add")
      */
    public function index(Request $request)
    {
        $form = $this->createFormBuilder()
            ->add('name', TextType::class)
            ->add('latitude', TextType::class)
            ->add('longitude', TextType::class)
            ->add('employee', TextType::class)
            ->getForm();
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            
            $entityManager = $this->getDoctrine()->getManager();
            $store = new Store();
            $store->setName($data['name']);
            $store->setLatitude($data['latitude']);
            $store->setLongitude($data['longitude']);
            $store->setEmployee($data['employee']);

            $entityManager->persist($store);
            $entityManager->flush();

            return $this->redirectToRoute('app_default_index');
        }
        return $this->render('add_store.html.twig', array('form' => $form->createView()));
    }
}