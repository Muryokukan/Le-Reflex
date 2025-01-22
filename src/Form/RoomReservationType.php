<?php

namespace App\Form;

use App\Entity\RoomReservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class RoomReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class)
            ->add('lastname', TextType::class)
            ->add('email', EmailType::class, [
                'required' => false,
            ])
            ->add('phoneNumber', TextType::class)
            ->add('reservationDate', DateType::class, [
                'required' => true,
            ])
            ->add('slot', EntityType::class, [
                'class' => 'App\Entity\RoomReservationSlot',
                'choice_label' => 'name',
                'choice_attr' => function ($slot) {
                    return [
                        'data-description' => $slot->getDescription()
                    ];
                },
                'required' => true,
                'expanded' => true,
                'multiple' => false,
            ])
            ->add('rooms', EntityType::class, [
                'class' => 'App\Entity\Room',
                'choice_label' => 'name',
                'choice_attr' => function ($room) {
                    return [
                        'data-price' => $room->getPrice(),
                        'data-description' => $room->getDescription(),
                        'data-options' => json_encode($room->getRoomOptions()->map(fn($option) => $option->getId())->toArray())
                    ];
                },
                'multiple' => true,
                'expanded' => true,
                'required' => true,
            ])
            ->add('roomOptions', EntityType::class, [
                'class' => 'App\Entity\RoomOption',
                'choice_label' => 'name',
                'choice_attr' => function ($option) {
                    return [
                        'data-price' => $option->getPrice(),
                        'data-description' => $option->getDescription()
                    ];
                },
                'multiple' => true,
                'expanded' => true,
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RoomReservation::class,
            'csrf_protection' => true,
        ]);
    }
}