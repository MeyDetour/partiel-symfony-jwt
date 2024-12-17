<?php

namespace App\Controller;

use App\Entity\Contribution;
use App\Entity\Event;
use App\Entity\Suggestion;
use App\Repository\ContributionRepository;
use App\Service\ValidatorService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api')]
class SuggestionController extends AbstractController
{
    #[Route('/event/{id}/create/suggestion', name: 'new_suggestion', methods: 'post')]
    public function create(Event $event, Request $request, EntityManagerInterface $manager, SerializerInterface $serializer): Response
    {
        // profile must be organisator or administrator
        if ($this->getUser()->getProfile() != $event->getOrganisator() and !$event->isProfileInAdministrators($this->getUser()->getProfile() )) {
            return $this->json(["message" => "Only author and administrators can do this action"], 401);
        }
        if (!ValidatorService::isValidEvent($event)) {
            return $this->json(["message" => "You cannot add suggestions to event"], 400);
        }
        $suggestion = $serializer->deserialize($request->getContent(), Suggestion::class, "json");
        if (!$suggestion->getDescription()) {
            return $this->json(["message" => "You must provide description message"], 400);
        }
        $suggestion->setEvent($event);
        $suggestion->setTaken(false);
        $manager->persist($suggestion);
        $manager->flush();

        return $this->json(["message" => "ok"], 201);

    }

    #[Route('/edit/suggestion/{id}', name: 'edit_suggestion', methods: 'put')]
    public function edit(Suggestion $suggestion, Request $request, EntityManagerInterface $manager, SerializerInterface $serializer): Response
    {
        $event = $suggestion->getEvent();

        // profile must be organisator or administrator
        if ($this->getUser()->getProfile() != $event->getOrganisator() and !$event->isProfileInAdministrators($this->getUser()->getProfile() )) {
            return $this->json(["message" => "Only author and administrators can do this action"], 401);
        }
        if (!ValidatorService::isValidEvent($event)) {
            return $this->json(["message" => "You cannot add suggestions to event"], 400);
        }
        $suggestionEdited = $serializer->deserialize($request->getContent(), Suggestion::class, "json");
        if (!$suggestionEdited->getDescription()) {
            return $this->json(["message" => "You must provide description message"], 400);
        }
        $suggestion->setDescription($suggestionEdited->getDescription());
        $manager->persist($suggestion);
        $manager->flush();

        return $this->json(["message" => "ok"], 201);

    }

    #[Route('/delete/suggestion/{id}', name: 'delete_suggestion', methods: 'delete')]
    public function delete(Suggestion $suggestion, Request $request, EntityManagerInterface $manager, SerializerInterface $serializer): Response
    {
        // profile must be organisator or administrator
        if ($this->getUser()->getProfile() != $suggestion->getEvent()->getOrganisator() and !$suggestion->getEvent()->isProfileInAdministrators($this->getUser()->getProfile() )) {
            return $this->json(["message" => "Only author and administrators can do this action"], 401);
        }
        $manager->remove($suggestion);
        $manager->flush();

        return $this->json(["message" => "ok"], 201);

    }

    #[Route('/suggestion/{id}', name: 'misshandle_suggestion', methods: 'DELETE')]
    public function misshandleSuggestion(Suggestion $suggestion, Request $request, EntityManagerInterface $manager, SerializerInterface $serializer): Response
    {
        if (!$this->getUser()->getProfile()->isEventInEventsOfUser($suggestion->getEvent())){
            return $this->json(["message" => "You are not participant of the event"], 400);
        }
        if (!ValidatorService::isValidEvent($suggestion->getEvent())) {
            return $this->json(["message" => "You cannot add suggestions to event"], 400);
        }
        if (!$suggestion->isTaken()) {
            return $this->json(["message" => "Suggestion is not taken"], 400);
        }
        if ($suggestion->getContribution()->getAuthor()!= $this->getUser()->getProfile() ) {
            return $this->json(["message" => "You are currently handling this suggestion"], 400);
        }
        $manager->remove($suggestion->getContribution());
        $manager->flush();


        $suggestion->setTaken(false);
        $suggestion->setContribution(null);
      $manager->persist($suggestion);
        $manager->flush();

        return $this->json(["message" => "ok"], 200);
    }
    #[Route('/suggestion/{id}', name: 'handle_suggestion', methods: 'PATCH')]
    public function handleSuggestion(Suggestion $suggestion, Request $request, EntityManagerInterface $manager, SerializerInterface $serializer,ContributionRepository $contributionRepository): Response
    {
        if (!$this->getUser()->getProfile()->isEventInEventsOfUser($suggestion->getEvent())){
            return $this->json(["message" => "You are not participant of the event"], 400);
        }
        if (!ValidatorService::isValidEvent($suggestion->getEvent())) {
            return $this->json(["message" => "You cannot add suggestions to event"], 400);
        }
        $cpntibutionExist = $contributionRepository->findOneBy(['author'=>$this->getUser()->getProfile(),"suggestion"=>$suggestion]);
        if ($suggestion->isTaken() or $cpntibutionExist) {
            return $this->json(["message" => "Suggestion already taken"], 400);
        }

        $contribution = new Contribution();
        $contribution->setSuggestion($suggestion);
        $contribution->setAuthor($this->getUser()->getProfile());
       $contribution->setEvent($suggestion->getEvent());
        $suggestion->setTaken(true);
        $manager->persist($contribution);
        $manager->persist($suggestion);
        $manager->flush();

        return $this->json(["message" => "ok"], 200);
    }


}
