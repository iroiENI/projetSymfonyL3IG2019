<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Category;
use App\Repository\ProductRepository;
use App\Service\Panier\PanierService;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{

    /**
     * @route("/",name="index")
     */
  public function index(Request $request, ProductRepository $repoProd ){

    $form= $this->createFormBuilder()
                ->add('designation')
                ->add('category', EntityType::class, [
                    'class' => Category::class,
                    'choice_label' => 'libelle'
                ])
                ->getForm();

    $form->handleRequest($request);

    if($form->isSubmitTed() && $form->isValid() ){

        $data = $form->getData();
        $produits = $repoProd->findBy($data);
    }
    else{
        $produits = $repoProd->findAll();
    }
    
    return $this->render('/home/index.html.twig', [
        'produit' => $produits,
        'form' =>$form->createView(),
    ]);
   
  }

  /**
     * @Route("/panier", name="panier_index")
     */
    public function panier(PanierService $panierService)
    {

        return $this->render('panier/index.html.twig', [
            'items' => $panierService->getFullCart(),
            'total' => $panierService->getTotal()
        ]);
    }

    /**
     * @route("/panier/add/{id}", name="panier_add")
     */
    public function add($id, PanierService $panierService){

        $panierService->add($id);
        return $this->redirectToRoute("panier_index");
    }

    /**
     * @route("/panier/remove/{id}", name="panier_remove")
     */
    public function remove($id, PanierService $panierService){
       
        $panierService->remove($id);

       return $this->redirectToRoute("panier_index");
    }

    /**
     * @Route("/produit/{id}/show", name="home_show")
     */
    public function show($id, ProductRepository $repoProd){
        $produit = $repoProd->find($id);

        return $this->render('/home/show.html.twig',[
            'product' => $produit
        ]);
    }

}
