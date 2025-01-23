<?php

namespace App\Mapper;

use App\DTO\ContactMessageDTO;
use App\Entity\ContactMessage;

class ContactMessageMapper
{
    public function toEntity(ContactMessageDTO $dto): ContactMessage
    {
        $entity = new ContactMessage();
        $entity->setFullname($dto->fullname)
            ->setEmail($dto->email)
            ->setPhone($dto->phone)
            ->setSubject($dto->subject)
            ->setMessage($dto->message)
            ->setSubmittedAt(new \DateTimeImmutable());

        return $entity;
    }
}