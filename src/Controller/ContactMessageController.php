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

final class ContactMessageController extends AbstractController
{
    // Disabled by 'condition: false'
    #[Route('/contact', name: 'app_contact', methods: ['GET', 'POST'], condition: 'false')]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        MailerService $mailer,
        ContactMessageMapper $mapper,
    ): Response {
        $form = $this->createForm(ContactMessageType::class, new ContactMessageDTO());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contactMessage = $mapper->toEntity($form->getData());

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
                    'warning',
                    'Votre message a bien été enregistré mais nous rencontrons des difficultés techniques pour envoyer la notification. Notre traiterons votre demande dès que possible et restons contactable directement par email ou téléphone.'
                );
            }

            return $this->redirectToRoute('app_contact');
        }

        return $this->render('contact_message/index.html.twig', [
            'form' => $form,
        ]);
    }
}
