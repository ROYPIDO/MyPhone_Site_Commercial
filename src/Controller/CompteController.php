<?php

namespace App\Controller;

use App\Form\AvatarType;
use App\Services\ImageManager;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CompteController extends AbstractController
{
    #[Route('/compte', name: 'app_compte')]
    public function index(Request $request, ImageManager $imageManager, UserRepository $userRepository): Response
    {
        $user=$this->getUser();
        $form = $this->createForm(AvatarType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // ici debut code image
           $imageManager->EnregistreImage($form,'avatar', $user ,'avatarDefault.jpg');
            // fin code image

            $userRepository->save($user, true);

            return $this->redirectToRoute('app_compte', []);
        }

        return $this->render('compte/index.html.twig', [
            'form' =>$form->createView()

        ]);
    }
}
