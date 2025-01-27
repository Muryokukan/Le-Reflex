<?php

namespace App\Controller;

use App\Repository\MenuImageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MenuImageController extends AbstractController
{
    #[Route('/menu', name: 'app_menu')]
    public function index(MenuImageRepository $menuImageRepository): Response
    {
        $menuImages = $menuImageRepository->findEnabledOrderedByDisplayOrder();

        return $this->render('menu/index.html.twig', [
            'menu_images' => $menuImages,
        ]);
    }
}
