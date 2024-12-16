<?php

namespace App\DataFixtures;

use App\Entity\Event;
use App\Entity\Profile;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setCreatedAt(new \DateTimeImmutable());
        $user->setEmail("mey@mey.com");
        $user->setRoles(["ROLE_ADMIN"]);
        $user->setPassword("$2y$13\$y96vwUoKB84JyBsBpd4qguHQHS0mMvlkKVKOWnZkbj2rKc4tWoifO");

        $userMax = new User();
        $userMax->setCreatedAt(new \DateTimeImmutable());
        $userMax->setEmail("max@max.com");
        $userMax->setRoles(["ROLE_ADMIN"]);
        $userMax->setPassword("$2y$13\$VTn1/HMjZa6Y6ODYtrfxa.6CKpBFQuYr7KO56TsmHlk90D3p1jFPm");


        $manager->persist($userMax);
        $manager->persist($user);
        $manager->flush();

        $profile = new Profile();
        $profile->setDisplayName("MeïMeï");
        $profile->setUserAssociated($user);
        $manager->persist($profile);
        $manager->flush();

  $profileMax = new Profile();
        $profileMax->setDisplayName("Max");
        $profileMax->setUserAssociated($userMax);
        $manager->persist($profileMax);

        $events = [
            [
                "name" => "premier evenement",
                "description" => "the description",
                "startDate" => "20.12.2024 9:50",
                "endDate" => "20.12.2024 10:43",
                "publicPlace" => false,
                "public" => true
            ], [
                "name" => "deuxieme evenement",
                "description" => "the description",
                "startDate" => "20.12.2024 9:50",
                "endDate" => "20.12.2024 10:43",
                "publicPlace" => true,
                "public" => true
            ], [
                "name" => "troisieme evenement",
                "description" => "the description",
                "startDate" => "20.12.2024 9:50",
                "endDate" => "20.12.2024 10:43",
                "publicPlace" => true,
                "public" => false
            ], [
                "name" => "quatrieme evenement",
                "description" => "the description",
                "startDate" => "20.12.2024 9:50",
                "endDate" => "20.12.2024 10:43",
                "publicPlace" => false,
                "public" => false
            ]
        ];
        foreach ($events as $event) {
            $eventCreated = new Event();
            $eventCreated->setCreatedAt(new \DateTimeImmutable());
            $eventCreated->setName($event['name']);
            $eventCreated->setDescription($event['description']);
            $eventCreated->setState("onSchedule");
            $eventCreated->setPublic($event['public']);
            $eventCreated->setPublicPlace($event['publicPlace']);
            $eventCreated->setProfile($profile);
            $eventCreated->setStartDate(DateTime::createFromFormat('d.m.Y h:i', $event['startDate']));
            $eventCreated->setEndDate(DateTime::createFromFormat('d.m.Y h:i', $event['endDate']));
            $manager->persist($eventCreated);
        }
        $manager->flush();
    }
}
