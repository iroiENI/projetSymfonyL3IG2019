<?php

namespace App\Service\Panier;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class PanierService{

    protected $session;
    protected $produitRepo;

    public function __construct(SessionInterface $session, ProductRepository $produitRepo){
        $this->session = $session;
        $this->produitRepo = $produitRepo;
    }

    public function add(int $id) {

        $panier = $this->session->get('panier', []); // prend un panier vide

        if(!empty($panier[$id])){
            $panier[$id]++;
        }
        else{
            $panier[$id] = 1; // ajout truc dans le panier
        }

        $this->session->set('panier', $panier); // mettre le panier dans la session
    }

    public function remove(int $id) {

        $panier = $this->session->get('panier', []);

        if(!empty($panier[$id])){
        unset($panier[$id]);
       }
       
       $this->session->set('panier', $panier);
    }

    public function getFullCart() : array {

        $panier = $this->session->get('panier', []);

        $panierDonnees=[];

        foreach($panier as $id => $quantite){
            $panierDonnees[] = [
                'produit' => $this->produitRepo->find($id),
                'quantite' => $quantite
            ];
        }
        return $panierDonnees;
    }

    public function getTotal() : float {
        
        $total = 0;

        foreach($this->getFullCart() as $item){
            $total += $item['produit']->getPu() * $item['quantite'];
        }

        return $total;
    }

    
}