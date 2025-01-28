<?php

namespace App\Controller;

use App\Repository\SliderImageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController{
    #[Route('/', name: 'app_home')]
    public function index(SliderImageRepository $sliderImageRepository): Response
    {
        $sliderImages = $sliderImageRepository->findAll();

        return $this->render('home/index.html.twig', [
            'sliderImages' => $sliderImages
        ]);
    }

    #[Route('/qrcode', name: 'app_qrcode')]
    public function qrcode(): Response
    {
        return $this->render('home/qrcode.html.twig');
    }
}
