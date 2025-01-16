<?php

namespace App\DataFixtures;

use App\Entity\ContactMessage;
use DateTimeImmutable;
use Faker\Factory;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ContactMessageFixtures extends Fixture
{
    private $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        $faker = $this->faker;
        for ($i = 0; $i < 10; $i++) {
            $submittedAt = $faker->dateTime('now');
            $submittedAt = DateTimeImmutable::createFromMutable( $submittedAt );
            $contact = new ContactMessage();
            $contact
                ->setFullname($faker->name())
                ->setEmail($faker->email())
                ->setPhone($faker->phoneNumber())
                ->setSubject($faker->text(50))
                ->setMessage($faker->text())
                ->setSubmittedAt($submittedAt)
                ->setIsRead($faker->boolean(20));

            $manager->persist($contact);
        }

        $manager->flush();
    }
}
