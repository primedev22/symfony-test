<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Entity\Store;

class EditStoreController extends AbstractController
{
    /**
      * @Route("/edit/{id}")
      */
    public function index($id, Request $request)
    {
        $store = $this->getDoctrine()
        ->getRepository(Store::class)
        ->find($id);

        if (!$store) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }
        $storeData = array(
            'id'        => $id, 
            'name'      => $store->getName(),
            'latitude'  => $store->getLatitude(),
            'longitude' => $store->getLongitude(),
            'employee'  => $store->getEmployee(),
        );       
        $form = $this->createFormBuilder($storeData)
            ->add('name', TextType::class)
            ->add('latitude', TextType::class)
            ->add('longitude', TextType::class)
            ->add('employee', TextType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            
            $entityManager = $this->getDoctrine()->getManager();
            $store->setName($data['name']);
            $store->setLatitude($data['latitude']);
            $store->setLongitude($data['longitude']);
            $store->setEmployee($data['employee']);

            $entityManager->persist($store);
            $entityManager->flush();

            return $this->redirectToRoute('app_default_index');
        }
        return $this->render('edit_store.html.twig', array('data' => $store, 'form' => $form->createView()));
    }
}