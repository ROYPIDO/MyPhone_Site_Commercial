<?php

namespace App\Controller;

use App\Entity\Tva;
use App\Form\TvaType;
use App\Repository\TvaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/tva')]
class TvaController extends AbstractController
{
    #[Route('/', name: 'app_tva_index', methods: ['GET'])]
    public function index(TvaRepository $tvaRepository): Response
    {
        return $this->render('tva/index.html.twig', [
            'tvas' => $tvaRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_tva_new', methods: ['GET', 'POST'])]
    public function new(Request $request, TvaRepository $tvaRepository): Response
    {
        $tva = new Tva();
        $form = $this->createForm(TvaType::class, $tva);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tvaRepository->save($tva, true);

            return $this->redirectToRoute('app_tva_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('tva/new.html.twig', [
            'tva' => $tva,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_tva_show', methods: ['GET'])]
    public function show(Tva $tva): Response
    {
        return $this->render('tva/show.html.twig', [
            'tva' => $tva,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_tva_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Tva $tva, TvaRepository $tvaRepository): Response
    {
        $form = $this->createForm(TvaType::class, $tva);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tvaRepository->save($tva, true);

            return $this->redirectToRoute('app_tva_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('tva/edit.html.twig', [
            'tva' => $tva,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_tva_delete', methods: ['POST'])]
    public function delete(Request $request, Tva $tva, TvaRepository $tvaRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tva->getId(), $request->request->get('_token'))) {
            $tvaRepository->remove($tva, true);
        }

        return $this->redirectToRoute('app_tva_index', [], Response::HTTP_SEE_OTHER);
    }
}
