<?php

namespace App\Controller;

use App\Repository\DailyMenuRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    private DailyMenuRepository $dailyMenuRepository;

    public function __construct(DailyMenuRepository $dailyMenuRepository)
    {
        $this->dailyMenuRepository = $dailyMenuRepository;
    }

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $dailyMenu = $this->dailyMenuRepository->findOneBy([], ['id' => 'DESC']);

        return $this->render('home/index.html.twig', [
            'dailyMenu' => $dailyMenu,
        ]);
    }

    #[Route('/qrcode', name: 'app_qrcode')]
    public function qrcode(): Response
    {
        return $this->render('home/qrcode.html.twig');
    }
}