<?php

namespace App\Controller;

use App\Repository\EventImageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class EventImageController extends AbstractController
{
    #[Route('/event', name: 'app_event')]
    public function index(EventImageRepository $eventImageRepository): Response
    {
        $eventImages = $eventImageRepository->findEnabledOrderedByDisplayOrder();

        return $this->render('event/index.html.twig', [
            'event_images' => $eventImages,
        ]);
    }
}
