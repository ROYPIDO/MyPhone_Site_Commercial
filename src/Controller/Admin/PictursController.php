<?php

namespace App\Controller\Admin;

use App\Entity\Picturs;
use App\Form\PictursType;
use App\Repository\PictursRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/picturs')]
class PictursController extends AbstractController
{
    #[Route('/', name: 'app_picturs_index', methods: ['GET'])]
    public function index(PictursRepository $pictursRepository): Response
    {
        return $this->render('picturs/index.html.twig', [
            'picturs' => $pictursRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_picturs_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PictursRepository $pictursRepository): Response
    {
        $pictur = new Picturs();
        $form = $this->createForm(PictursType::class, $pictur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pictursRepository->save($pictur, true);

            return $this->redirectToRoute('app_picturs_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('picturs/new.html.twig', [
            'pictur' => $pictur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_picturs_show', methods: ['GET'])]
    public function show(Picturs $pictur): Response
    {
        return $this->render('picturs/show.html.twig', [
            'pictur' => $pictur,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_picturs_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Picturs $pictur, PictursRepository $pictursRepository): Response
    {
        $form = $this->createForm(PictursType::class, $pictur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pictursRepository->save($pictur, true);

            return $this->redirectToRoute('app_picturs_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('picturs/edit.html.twig', [
            'pictur' => $pictur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_picturs_delete', methods: ['POST'])]
    public function delete(Request $request, Picturs $pictur, PictursRepository $pictursRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$pictur->getId(), $request->request->get('_token'))) {
            $pictursRepository->remove($pictur, true);
        }
        $path = $this->getParameter('upload_dir') . '/' . $pictur->getName();
        if (file_exists($path)) {
            unlink($path);
        }
        return $this->redirectToRoute('app_produit_show', ['id' => $pictur->getProduit()->getId()], Response::HTTP_SEE_OTHER);
    }
}
