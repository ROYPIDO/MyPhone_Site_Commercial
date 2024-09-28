<?php

namespace App\Controller;

use App\Services\Panier;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PanierController extends AbstractController
{
    #[Route('/panier', name: 'app_panier')]
    public function index(Panier $panier): Response
    {
       
        return $this->render('panier/index.html.twig', 
        
        [
           'panier' => $panier->getDetailPanier(),
           'total_panier' =>$panier->getTotal()
        ]);
    }

    #[Route('/ajouter-panier/{id}', name: 'app_panier_add_produit')]
    public function add($id , Panier $panier): Response
    {
       $panier->addProduitPanier($id);
       return $this->redirectToRoute('app_panier');
       
    }

    #[Route('/delete-quantite-panier/{id}', name: 'app_panier_delete_quantity')]
    public function deleteQantity($id , Panier $panier): Response
    {
       $panier->deleteQuantityProduit($id);
       return $this->redirectToRoute('app_panier');
       
    }

    #[Route('/delete-produit-panier/{id}', name: 'app_panier_delete_produit')]
    public function deleteproduit($id , Panier $panier): Response
    {
       $panier->deleteProduitPanier($id);
       return $this->redirectToRoute('app_panier');
       
    }

}
