<?php

namespace App\Controller\Admin;

use App\Entity\Picturs;
use App\Entity\Produit;
use App\Form\PictursType;
use App\Form\ProduitType;
use App\Services\ImageManager;
use App\Repository\PictursRepository;
use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/produit')]
class ProduitController extends AbstractController
{
    #[Route('/', name: 'app_produit_index', methods: ['GET'])]
    public function index(ProduitRepository $produitRepository): Response
    {
        return $this->render('produit/index.html.twig', [
            'produits' => $produitRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_produit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ProduitRepository $produitRepository,
                        ImageManager $imageManager): Response
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // ici debut code image
           $imageManager->EnregistreImage($form,'image', $produit ,'defaultImage.jpg');
            // fin code image

            $produitRepository->save($produit, true);

            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('produit/new.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/voir-produit/{id}', name: 'app_produit_show')]
    public function show(Produit $produit,
                         Request $request,
                         PictursRepository $pictursRepository,
                         ImageManager $imageManager): Response
    {
        // ici code ajoue image
        $pictur = new Picturs();
        $form = $this->createForm(PictursType::class, $pictur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //code image
            $imageManager->EnregistreImage($form,'name', $pictur ,'defaultImage.jpg');
            //fin image
            $pictur->setProduit($produit);
            $pictursRepository->save($pictur, true);

            return $this->redirectToRoute('app_produit_show', ['id' => $produit->getId()], Response::HTTP_SEE_OTHER);
        }
        // ici fin code ajoue image

        return $this->render('produit/show.html.twig', [
            'produit' => $produit,
            'form' =>$form->createView()
        ]);
    }

    #[Route('/{id}/edit', name: 'app_produit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Produit $produit, 
                         ProduitRepository $produitRepository,
                         ImageManager $imageManager): Response
    {
        $old_name_image = $produit->getImage();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
          
             // ici debut code image
            $imageManager->EnregistreImage($form ,'image', $produit ,$old_name_image);
             // fin code image
            $produitRepository->save($produit, true);

            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_produit_delete', methods: ['POST'])]
    public function delete(Request $request, Produit $produit, ProduitRepository $produitRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$produit->getId(), $request->request->get('_token'))) {
            $produitRepository->remove($produit, true);
        }

        return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
    }
}
