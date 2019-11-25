<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Category;
use App\Form\ProductType;
use App\Repository\ClientRepository;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(Request $request,ObjectManager $manager, ProductRepository $repo, CategoryRepository $repoCat)
    {
        $categorie = new Category();
        $form= $this->createFormBuilder($categorie)
                    ->add('libelle')
                    ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitTed() && $form->isValid() ){
            $manager->persist($categorie);
            $manager->flush();
        }

        $produit = $repo->findAll();
        $category = $repoCat->findAll();

        return $this->render('admin/index.html.twig', [
            'product' => $produit,
            'category' => $category,
            'form' =>$form->createView(),
        ]);
    }

    /**
     * @Route("/admin/formulaire", name="formulaire")
     * @Route("/admin/{id}/edit", name="product_edit")
     */
     public function formulaire(Product $produit = null, Request $request, ObjectManager $manager){
        
        if(!$produit){
            $produit = new Product();
        }

        $form = $this->createForm(ProductType::class, $produit);

        $form->handleRequest($request);

        if($form->isSubmitted()&& $form->isValid()){

            $uploadedFile = $form['image']->getData();
            if ($uploadedFile) {
                $destination = $this->getParameter('kernel.project_dir').'/public/uploads';
                $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = Urlizer::urlize($originalFilename).'-'.uniqid().'.'.$uploadedFile->guessExtension();
                $uploadedFile->move(
                    $destination,
                    $newFilename
                );
                $produit->setImage($newFilename);
            }
        
            $manager->persist($produit);
            $manager->flush();
            
            return $this->redirectToRoute('admin');
        }

       return $this->render('admin/formulaire.html.twig', [
           'formProduct' => $form->createView(),
           'editMod' => $produit->getId() !== null
       ]);
     }

     /**
      * @Route("/deleteProduit/{id}", name="admin_delete")
      */
     public function DeleteProduct($id, ProductRepository $repo, ObjectManager $manager){
        $produit = $repo->find($id);
        $manager->remove($produit);
        $manager->flush();

        return $this->redirectToRoute('admin');
     }

     /**
      * @Route("/admin/client", name="admin_client")
      */
     public function listeCli(ClientRepository $repoCli){

        $clients = $repoCli->findAll();
    
        return $this->render('/admin/listeClient.html.twig', [
        'client' => $clients,
    ]);
     }

     /**
      * @Route("/delete/client/{id}", name="client_delete")
      */
     public function deleteCli($id, ClientRepository $repoCli, ObjectManager $manager){
        $client = $repoCli->find($id);
        $manager->remove($client);
        $manager->flush();

        return $this->redirectToRoute('admin_client');
     }
}
