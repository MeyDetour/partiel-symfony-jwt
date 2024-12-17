<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Profile;
use App\Entity\User;
use App\Repository\ContributionRepository;
use App\Repository\EventRepository;
use App\Repository\ProfileRepository;
use App\Repository\UserRepository;
use App\Service\ValidatorService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api')]
class EventController extends AbstractController
{
    //==============================================================ACTIONS
    #[Route('/event/{id}', name: 'canceled_event', methods: "patch")]
    public function canceledEvent(EntityManagerInterface $entityManager, Event $event): Response
    {
        // profile must be organisator or administrator
        if ($this->getUser()->getProfile() != $event->getOrganisator() and !$event->isProfileInAdministrators($this->getUser()->getProfile())) {
            return $this->json(["message" => "Only author and administrators can do this action"], 401);
        }
        if (!ValidatorService::isValidEvent($event)) {
            return $this->json(["message" => "Event is passed or canceled"], 401);
        }
        $event->setState("canceled");
        $entityManager->persist($event);
        $entityManager->flush();
        return $this->json(["message" => "ok"], 200);

    }

    #[Route('/create/event', name: 'new_event', methods: 'post')]
    public function create(SerializerInterface $serializer, EntityManagerInterface $entityManager, Request $request): Response
    {

        $event = $serializer->deserialize($request->getContent(), Event::class, 'json');

        if (!$event->getName() or trim($event->getName(), ' ') == "" or !$event->getDescription() or trim($event->getDescription(), ' ') == "") {
            return $this->json(["message" => "Name and description must be not null "], 400);
        }
        if (($event->isPublic() != false and $event->isPublic() != true) or ($event->isPublicPlace() != false and $event->isPublicPlace() != true) or !$event->getEndDate() or !$event->getStartDate()) {
            return $this->json(["message" => "You must provide this fields : startDate, endDate, publicPlace(boolean) , public(boolean) "], 400);
        }

        // end date after start date
        if ($event->getEndDate() <= $event->getStartDate()) {
            return $this->json(["message" => "End date must be after start date"], 200);
        } // start date after today)
        else if ($event->getStartDate() <= new \DateTime()) {
            return $this->json(["message" => "Start date must be after today"], 200);
        } // end date after today
        else if ($event->getEndDate() <= new \DateTime()) {
            return $this->json(["message" => "End date must be after today"], 200);
        }
        $event->setState("onSchedule");
        $event->setCreatedAt(new \DateTimeImmutable());
        $event->setOrganisator($this->getUser()->getProfile());
        $event->addParticipant($this->getUser()->getProfile());
        $entityManager->persist($event);
        $entityManager->flush();
        return $this->json(["message" => "ok"], 201);

    }

    #[Route('/edit/event/{id}', name: 'edit_event', methods: "put")]
    public function edit(SerializerInterface $serializer, Event $event, EntityManagerInterface $entityManager, Request $request): Response
    {
        // profile must be organisator or administrator
        if ($this->getUser()->getProfile() != $event->getOrganisator() and !$event->isProfileInAdministrators($this->getUser()->getProfile())) {
            return $this->json(["message" => "Only author and administrators can do this action"], 401);
        }

        if ($event->getEndDate() <= $event->getStartDate() or $event->getStartDate() <= new \DateTime() or $event->getEndDate() <= new \DateTime()) {
            return $this->json(["message" => "You cannot edit an old event"], 401);
        }

        $eventEdited = $serializer->deserialize($request->getContent(), Event::class, 'json');


        $event->setName($eventEdited->getName());
        $event->setDescription($eventEdited->getDescription());
        $event->setPublic($eventEdited->isPublic());
        $event->setPublicPlace($eventEdited->isPublicPlace());
        $event->setStartDate($eventEdited->getStartDate());
        $event->setEndDate($event->getEndDate());

        // end date after start date
        if ($event->getEndDate() <= $event->getStartDate()) {
            return $this->json(["message" => "End date must be after start date"], 200);
        } // start date after today
        else if ($event->getStartDate() <= new \DateTime()) {
            return $this->json(["message" => "Start date must be after today"], 200);
        } // end date after today
        else if ($event->getEndDate() <= new \DateTime()) {
            return $this->json(["message" => "End date must be after today"], 200);
        }

        $entityManager->persist($event);
        $entityManager->flush();
        return $this->json(["message" => "ok"], 200);

    }

    #[Route('/join/event/{id}', name: 'join_public_event', methods: "PATCH")]
    public function joinPublicEvent(Event $event, EntityManagerInterface $manager): Response
    {
        if (!$event->isPublic()) {
            return $this->json(["message" => "Event must be public"], 400);
        }
        if (!ValidatorService::isValidEvent($event)) {
            return $this->json(["message" => "This event is passed or canceled"], 401);
        }

        $event->addParticipant($this->getUser()->getProfile());
        $manager->persist($event);
        $manager->flush();
        return $this->json(["message" => "ok"], 200);

    }

    #[Route('/left/event/{id}', name: 'remove_public_event', methods: "PATCH")]
    public function leftPublicEvent(Event $event, EntityManagerInterface $manager, ContributionRepository $contributionRepository): Response
    {
        if ($event->getOrganisator() == $this->getUser()->getProfile()) {
            return $this->json(["message" => "Organisator cannot left its own event its illogic"], 400);
        }

        if (!$event->isPublic()) {
            return $this->json(["message" => "Event must be public"], 400);
        }
        if (!ValidatorService::isValidEvent($event)) {
            return $this->json(["message" => "This event is passed or canceled"], 401);
        }

        $event->removeParticipant($this->getUser()->getProfile());
        $contributions = $contributionRepository->findBy(['event' => $event, "author" => $this->getUser()->getProfile()]);
        foreach ($contributions as $contribution) {
            $manager->remove($contribution);
        }


        $manager->persist($event);
        $manager->flush();
        return $this->json(["message" => "ok"], 200);

    }

    #[Route('/event/{id}/add/profile/{profileId}/as/administrator', name: 'add_administrator', methods: "PATCH")]
    public function addAdministrator(Event $event, int $profileId, EntityManagerInterface $manager, ProfileRepository $profileRepository): Response
    {
        //only organisator can add administrator not admin
        if ($event->getOrganisator() != $this->getUser()->getProfile()) {
            return $this->json(["message" => "You must be organisator to add administrator"], 400);
        }
        if (!ValidatorService::isValidEvent($event)) {
            return $this->json(["message" => "This event is passed or canceled"], 401);
        }
        $profile = $profileRepository->find($profileId);
        if (!$profile) {
            return $this->json(["message" => "Profile not found"], 404);
        }
        if (!$event->isProfileInParticipants($profile)) {
            return $this->json(["message" => "User must be in participants"], 400);
        }

        $event->addAdministrator($profile);

        $manager->persist($event);
        $manager->flush();
        return $this->json(["message" => "ok"], 200);

    }

    #[Route('/event/{id}/remove/profile/{profileId}/from/administrators', name: 'remove_administrator', methods: "DELETE")]
    public function removeAdministrator(Event $event, int $profileId, EntityManagerInterface $manager, ProfileRepository $profileRepository): Response
    {
        // only organisator can remove admin not admin
        if ($event->getOrganisator() != $this->getUser()->getProfile()) {
            return $this->json(["message" => "You must be organisator to add administrator"], 400);
        }
        if (!ValidatorService::isValidEvent($event)) {
            return $this->json(["message" => "This event is passed or canceled"], 401);
        }
        $profileId = $profileRepository->find($profileId);
        if (!$profileId) {
            return $this->json(["message" => "Profile not found"], 404);
        }
        if (!$event->isProfileInParticipants($profileId)) {
            return $this->json(["message" => "User must be in participants"], 400);
        }

        $event->removeAdministrator($profileId);

        $manager->persist($event);
        $manager->flush();
        return $this->json(["message" => "ok"], 200);

    }



    //==============================================================GETTERS

    //get  private event with wich we are invited  and public events
    #[Route('/events', name: 'get_events', methods: "get")]
    public function getEvents(EventRepository $eventRepository): Response
    {
        //public events
        $events = $eventRepository->getNextPublicEvent();

        foreach ($this->getUser()->getProfile()->getInvitations() as $invitation) {
            $event = $invitation->getEvent();
            if (ValidatorService::isValidEvent($event)) {
                $events[] = $event;
            }
        }

        //you are participant of event
        foreach ($this->getUser()->getProfile()->getEventsWichProfileParticip() as $event) {
            if (ValidatorService::isValidEvent($event) && !$event->isPublic()) {
                $events[] = $event;
            }
        }

        //you are creator of event
        foreach ($this->getUser()->getProfile()->getEvents() as $event) {
            if (ValidatorService::isValidEvent($event) && !$event->isPublic()) {
                $events[] = $event;
            }
        }
        return $this->json($events, 200, [], ['groups' => ['getEvents']]);
    }

    //get only public events
    #[Route('/events/public', name: 'get_public_events', methods: "get")]
    public function getEventsPublic(EventRepository $eventRepository): Response
    {
        $events = $eventRepository->getNextPublicEvent();
        return $this->json($events, 200, [], ['groups' => ['getEvents']]);
    }

    //get only private events with wich we are invited
    #[Route('/events/private', name: 'get_private_events', methods: "get")]
    public function getPrivateEvents(EventRepository $eventRepository): Response
    {

        //you are invited to event
        $events = [];
        foreach ($this->getUser()->getProfile()->getInvitations() as $invitation) {
            $event = $invitation->getEvent();
            if (ValidatorService::isValidEvent($event)) {
                $events[] = $event;
            }
        }

        //you are participant of event
        foreach ($this->getUser()->getProfile()->getEventsWichProfileParticip() as $event) {
            if (ValidatorService::isValidEvent($event) && !$event->isPublic()) {
                $events[] = $event;
            }
        }

        //you are creator of event
        foreach ($this->getUser()->getProfile()->getEvents() as $event) {
            if (ValidatorService::isValidEvent($event) && !$event->isPublic()) {
                $events[] = $event;
            }
        }
        return $this->json($events, 200, [], ['groups' => ['getEvents']]);
    }

    //METHODE FOR DEBUG get all events of db
    #[Route('/events/all', name: 'all_events', methods: "get")]
    public function allEvents(EventRepository $eventRepository, Request $request): Response
    {

        if (!in_array("ROLE_ADMIN", $this->getUser()->getRoles())) {
            return $this->json(["message" => "you are not admin"], 401);
        }
        $events = $eventRepository->findAll();

        return $this->json($events, 200, [], ['groups' => ['getEvents']]);
    }

    // get juste one event participants,invitations etc with some conditions
    #[Route('/event/{id}', name: 'get_event', methods: "get")]
    public function getEvent(EventRepository $eventRepository, Event $event): Response
    {


        //  you can see only active events
        //comment this line to see all event includes passsed events ( this keep restriction for private event)
        // if event is passed organisator can ( quand meme) see it's event
        if (!ValidatorService::isValidEvent($event) && $event->getOrganisator() != $this->getUser()->getProfile()) {
            return $this->json(["Event is finished or canceled"], 401);
        }


        //if its private event we check if your invited or you are participant or organisator
        // allow to see participants and invitations status only if event is private
        if (!ValidatorService::isAuthorizedToSeePrivateEvent($this->getUser(), $event)) {
            return $this->json(["Event is private and you are not invited"], 401);
        }

        //invitationss doesnt exist in the context of public event, we return [] for public event
        return $this->json($event, 200, [], ['groups' => ['getDetailOfPrivateEvent']]);
    }


    //==============================================================VERIFICATOR


    //TO DO

    #[Route('/search/event/{searchTerm}', name: 'search_event')]
    public function searchEvent(SerializerInterface $serializer, $searchTerm, EventRepository $eventRepository, Request $request): Response
    {
        if (trim($searchTerm, ' ') == "") {
            return $this->json(["message" => "SearchTerm must not be empty"], 401);

        }

        $events = [];
        $eventsData = $eventRepository->searchInUser($searchTerm);
        foreach ($eventsData as $event) {
            if ($event->isPublic()) {
                $events[] = $event;
            }
            if (ValidatorService::isValidEvent($event) and ValidatorService::isAuthorizedToSeePrivateEvent($this->getUser(),$event)) {
                $events[] = $event;
            }

        }
        return $this->json($events, 200, [], ['groups' => ['getEvents']]);
    }

}
