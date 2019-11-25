<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\CreateAccountType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/createAccount", name="security_account")
     */
    public function CreateAccount( Request $req, ObjectManager $manager, UserPasswordEncoderInterface $encoder)
    {
        $client = new Client();
        $form = $this->createForm(CreateAccountType:: class, $client);

        $form->handleRequest($req);

        if($form->isSubmitted() && $form->isValid()){
            $hash = $encoder->encodePassword($client, $client->getPassword());

            $client->setPassword($hash);

            $manager->persist($client);
            $manager->flush();

            return $this->redirectToRoute('security_connexion');
        }

        return $this->render('security/createAccount.html.twig', [
            'form' => $form->createView() 
        ]);
    }

    /**
     * @Route("/connexion", name="security_connexion")
     */
    public function login(){
        $error = "Veuillez reeseyer!";

        return $this->render("security/login.html.twig",[
            'error' => $error
        ]);
    }

     /**
     * @Route("/deconnexion", name="security_deconnexion")
     */
    public function logout(){
    }


}
