<?php

namespace App\Controller;

use App\Form\UserDonneeType;
use App\Services\Panier;
use App\Services\CommandeManager;
use App\Repository\CommandeRepository;
use App\Repository\DetailCommandeRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class AchatController extends AbstractController
{
    #[Route('/achat', name: 'app_achat')]
    public function index(
        CommandeRepository $commandeRepository,
        CommandeManager $commandeManager,
        Panier $panier,
        DetailCommandeRepository $detailCommandeRepository
    ): Response {


        $user = $commandeManager->getUser();
        if ($user) {
            if (is_null($user->getNom()) or is_null($user->getPrenom()) or is_null($user->getadresse()))  {
              return $this->redirectToRoute('app_userForm');
            }
        }else{
            return $this->redirectToRoute('app_login');
        }


        $commande = $commandeManager->getCommande($panier);
        $commandeRepository->save($commande, true);

        $detailPanier = $panier->getDetailPanier();
        foreach ($detailPanier as $ligne_panier) {
            $detailCommande = $commandeManager->getDetailCommande($commande, $ligne_panier);
            $detailCommandeRepository->save($detailCommande);
        }
        $detailCommandeRepository->save($detailCommande ,true);
        $panier->deletePanier();
        return $this->redirectToRoute('app_home');
    }

    #[Route('/donnee-utilisateur', name: 'app_userForm')]
    public function userForm(Request $request,UserRepository $userRepository)
    {
        $user = $this->getUser();
        $form = $this->createForm(UserDonneeType::class , $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
           $userRepository->save($user , true);
           return $this->redirectToRoute('app_achat');
        }
        return $this->render('achat/userForm.html.twig', [
           'form' => $form->createView()
        ]);
    }

}
