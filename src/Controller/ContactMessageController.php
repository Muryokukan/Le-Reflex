<?php

namespace App\Controller;

use App\Entity\ContactMessage;
use App\Form\ContactMessageType;
use App\Service\MailerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ContactMessageController extends AbstractController{
    #[Route('/contact', name: 'app_contact', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        MailerService $mailer,
    ): Response {
        $contactMessage = new ContactMessage();
        $form = $this->createForm(ContactMessageType::class, $contactMessage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contactMessage->setSubmittedAt(new \DateTimeImmutable());

            $entityManager->persist($contactMessage);
            $entityManager->flush();

            try {
                $mailer->sendContactNotification($contactMessage);
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
            'contact_message' => $contactMessage,
            'form' => $form,
        ]);
    }
}
