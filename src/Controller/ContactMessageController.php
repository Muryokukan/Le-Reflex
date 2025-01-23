<?php

namespace App\Controller;

use App\DTO\ContactMessageDTO;
use App\Form\ContactMessageType;
use App\Mapper\ContactMessageMapper;
use App\Service\MailerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ContactMessageController extends AbstractController{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly MailerService $mailer,
        private readonly ContactMessageMapper $mapper,
    ) {
    }

    #[Route('/contact', name: 'app_contact', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $form = $this->createForm(ContactMessageType::class, new ContactMessageDTO());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contactMessage = $this->mapper->toEntity($form->getData());
            
            $this->entityManager->persist($contactMessage);
            $this->entityManager->flush();

            try {
                $this->mailer->sendContactNotification($contactMessage);
                $this->addFlash(
                    'success', 
                    'Votre message a été envoyé avec succès.'
                );
            } catch (\Exception $e) {
                $this->addFlash(
                    'error',
                    'Une erreur est survenu lors de l\'envoi de votre message. Veuillez réessayer plus tard ou nous contacter directement par email ou téléphone.'
                );
            }

            return $this->redirectToRoute('app_contact');
        }

        return $this->render('contact_message/index.html.twig', [
            'form' => $form,
        ]);
    }
}
