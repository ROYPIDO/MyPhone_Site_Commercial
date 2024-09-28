<?php

namespace App\Controller;

use App\Entity\Carousel;
use App\Form\CarouselType;
use App\Repository\CarouselRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/carousel')]
class CarouselController extends AbstractController
{
    #[Route('/', name: 'app_carousel_index', methods: ['GET'])]
    public function index(CarouselRepository $carouselRepository): Response
    {
        return $this->render('carousel/index.html.twig', [
            'carousels' => $carouselRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_carousel_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CarouselRepository $carouselRepository): Response
    {
        $carousel = new Carousel();
        $form = $this->createForm(CarouselType::class, $carousel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $carouselRepository->save($carousel, true);

            return $this->redirectToRoute('app_carousel_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('carousel/new.html.twig', [
            'carousel' => $carousel,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_carousel_show', methods: ['GET'])]
    public function show(Carousel $carousel): Response
    {
        return $this->render('carousel/show.html.twig', [
            'carousel' => $carousel,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_carousel_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Carousel $carousel, CarouselRepository $carouselRepository): Response
    {
        $form = $this->createForm(CarouselType::class, $carousel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $carouselRepository->save($carousel, true);

            return $this->redirectToRoute('app_carousel_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('carousel/edit.html.twig', [
            'carousel' => $carousel,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_carousel_delete', methods: ['POST'])]
    public function delete(Request $request, Carousel $carousel, CarouselRepository $carouselRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$carousel->getId(), $request->request->get('_token'))) {
            $carouselRepository->remove($carousel, true);
        }

        return $this->redirectToRoute('app_carousel_index', [], Response::HTTP_SEE_OTHER);
    }
}
