<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class ContactMessageDTO
{
    public function __construct(
        #[Assert\NotBlank(message: 'Le nom complet est requis')]
        #[Assert\Length(min: 2, max: 255, minMessage: 'Le sujet doit contenir entre {{ min }} et {{ max }} caractères', maxMessage: 'Le sujet doit contenir entre {{ min }} et {{ max }} caractères')]
        public ?string $fullname = null,

        #[Assert\NotBlank(message: 'L\'email est requis')]
        #[Assert\Email(message: 'L\'email {{ value }} n\'est pas valide')]
        public ?string $email = null,

        #[Assert\Regex(pattern: '/^[0-9\s\+\-\.]+$/', message: 'Le numéro de téléphone n\'est pas valide')]
        public ?string $phone = null,

        #[Assert\NotBlank(message: 'Le sujet est requis')]
        #[Assert\Length(min: 3, max: 255, minMessage: 'Le sujet doit contenir entre {{ min }} et {{ max }} caractères', maxMessage: 'Le sujet doit contenir entre {{ min }} et {{ max }} caractères')]
        public ?string $subject = null,

        #[Assert\NotBlank(message: 'Le message est requis')]
        #[Assert\Length(min: 10, minMessage: 'Le message doit contenir au moins {{ limit }} caractères')]
        public ?string $message = null,
    ) {
    }
}
