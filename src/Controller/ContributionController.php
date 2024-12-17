<?php

namespace App\Controller;

use App\Entity\Contribution;
use App\Entity\Event;
use App\Entity\Suggestion;
use App\Service\ValidatorService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api')]
class ContributionController extends AbstractController
{
    #[Route('/event/{id}/suggestion/rebelle', name: 'rebelle_suggestion', methods: 'post')]
    public function createContribution(Event $event, Request $request, EntityManagerInterface $manager, SerializerInterface $serializer): Response
    {
        if (!ValidatorService::isValidEvent($event)) {
            return $this->json(["message" => "Event is passed or canceled"], 400);
        }
        if (!$this->getUser()->getProfile()->isEventInEventsOfUser($event)){
            return $this->json(["message" => "You are not participant of the event"], 400);
        }

        $contribution = $serializer->deserialize($request->getContent(), Contribution::class, "json");
        if (trim($contribution->getDescription(), ' ') == '') {
            return $this->json(["message" => "You must provide description"], 400);
        }
        $contribution->setAuthor($this->getUser()->getProfile());
        $contribution->setEvent($event);
        $manager->persist($contribution);
        $manager->flush();

        return $this->json(["message" => "ok"], 200);

    }

    #[Route('/edit/contribution/{id}', name: 'edit_contribution', methods: 'put')]
    public function edit(Contribution $contribution, Request $request, EntityManagerInterface $manager, SerializerInterface $serializer): Response
    {
        $event = $contribution->getEvent();
        if ($contribution->getAuthor() != $this->getUser()->getProfile()) {
            return $this->json(["message" => "Only owner can edit contribution"], 400);
        }
        if ($contribution->getSuggestion()) {
            return $this->json(["message" => "Only rebelle contribution can be modified"], 400);
        }
        if (!ValidatorService::isValidEvent($event)) {
            return $this->json(["message" => "You cannot add suggestions to event"], 400);
        }
        $contributionEdited = $serializer->deserialize($request->getContent(), Contribution::class, "json");
        if (!$contributionEdited->getDescription()) {
            return $this->json(["message" => "You must provide description message"], 400);
        }
        $contribution->setDescription($contributionEdited->getDescription());
        $manager->persist($contribution);
        $manager->flush();

        return $this->json(["message" => "ok"], 201);

    }

    #[Route('/delete/contribution/{id}', name: 'delete_contribution', methods: 'delete')]
    public function delete(Contribution $contribution, Request $request, EntityManagerInterface $manager, SerializerInterface $serializer): Response
    {
        $event = $contribution->getEvent();
        if ($contribution->getAuthor() != $this->getUser()->getProfile()) {
            return $this->json(["message" => "Only owner can edit contribution"], 400);
        }
        if ($contribution->getSuggestion()) {
            return $this->json(["message" => "Only rebelle contribution can be modified"], 400);
        }
        if (!ValidatorService::isValidEvent($event)) {
            return $this->json(["message" => "You cannot add suggestions to event"], 400);
        }
         $manager->remove($contribution);
        $manager->flush();

        return $this->json(["message" => "ok"], 201);

    }
}
