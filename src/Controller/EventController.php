<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Profile;
use App\Entity\User;
use App\Repository\EventRepository;
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

    #[Route('/create/event', name: 'new_event',methods: 'post')]
    public function create(SerializerInterface $serializer, EntityManagerInterface $entityManager, Request $request): Response
    {

        $event = $serializer->deserialize($request->getContent(), Event::class, 'json');

        if (!$event->getName() or trim($event->getName(), ' ') == "" or !$event->getDescription() or trim($event->getDescription(), ' ') == "") {
            return $this->json(["message" => "Name and description must be not null "], 400);
        }
        if ($event->isPublic() == null or !$event->getEndDate() or !$event->getStartDate()) {
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
        $event->setProfile($this->getUser()->getProfile());
        $entityManager->persist($event);
        $entityManager->flush();
        return $this->json(["message" => "ok"], 201);

    }

    #[Route('/edit/event/{id}', name: 'edit_event',methods: "put")]
    public function edit(SerializerInterface $serializer, Event $event, EntityManagerInterface $entityManager, Request $request): Response
    {
        if ($this->getUser()->getProfile() != $event->getProfile()) {
            # ad administrator permission
            return $this->json(["message" => "Only author can edit this event"], 401);
        }
        if ($event->getEndDate() <= $event->getStartDate() or  $event->getStartDate() <= new \DateTime() or $event->getEndDate() <= new \DateTime()) {
            return $this->json(["message" => "You cannot edit an old event"], 401);
        }

        $eventEdited = $serializer->deserialize($request->getContent(), Event::class, 'json');


        $event->setName($eventEdited->getName());
        $event->setDescription($eventEdited->getDescription());
        $event->setState($eventEdited->isPublic());
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

    #[Route('/events', name: 'get_events',methods: "get")]
    public function getEvents(EventRepository $eventRepository, Request $request): Response
    {
        $events = $eventRepository->getNextPublicEvent();
        foreach ($this->getUser()->getProfile()->getInvitations() as $invitation) {
            $event = $invitation->getEvent();
            if (self::isValidEvent($event)) {
                $events[] = $event;
            }
        }
        return $this->json($events, 200, [], ['groups' => ['getEvents']]);
    }

    //METHODE FOR DEBUG
    #[Route('/events/all', name: 'all_events',methods: "get")]
    public function allEvents(EventRepository $eventRepository, Request $request): Response
    {

        if (!in_array("ROLE_ADMIN", $this->getUser()->getRoles())){
            return $this->json(["message"=>"you are not admin"], 401);
        }
        $events = $eventRepository->findAll();

        return $this->json($events, 200, [], ['groups' => ['getEvents']]);
    }

    #[Route('/event/{id}', name: 'get_event',methods: "get")]
    public function getEvent(EventRepository $eventRepository, Event $event): Response
    {
        $now = new \DateTime();
        $profile = $this->getUser()->getProfile();

        //  we can show passed event
        // uncomment this line to avoid to see passed event
        /*if ($event->getStartDate() < $now) {
            return $this->json(["Event is finished"], 401);
        }*/

        //assert that its public event , if not we check if your invited
        if (self::isAuthorizedToSeePrivateEvent($this->getUser(),$event)) {
            return $this->json(["Event is private and you are not invited"], 401);
        }


        return $this->json($event, 200, [], ['groups' => ['getEvents']]);
    }

    #[Route('/event/{id}', name: 'canceled_event',methods: "patch")]
    public function canceledEvent(EntityManagerInterface $entityManager, Event $event): Response
    {
        if ($this->getUser()->getProfile() != $event->getProfile()) {
            # ad administrator permission
            return $this->json(["message" => "Only author can edit this event"], 401);
        }
        if (!self::isValidEvent($event)){
            return $this->json(["message" => "Event is passed or canceled"], 401);
        }
        $event->setState("canceled");
        $entityManager->persist($event);
        $entityManager->flush();
        return $this->json(["message" => "ok"], 200);

    }


    static function isValidEvent(Event $event):bool{
        $now = new \DateTime();
        if ($event->getStartDate() > $now and $event->getState() == "onSchedule") {
            return true;
        }
        return false;
    }
    static function isAuthorizedToSeePrivateEvent( $user,Event $event):bool{

        $profile=$user->getProfile();
        if (!$event->isPublic() and !$profile->isEventInEventsOfUser($event) and !$profile->isEventInInvited($event)) {
            return false;
        }
        return true;
    }





    //TO DO

    #[Route('/search/event/{searchTerm}', name: 'search_event')]
    public function searchEvent(SerializerInterface $serializer, $searchTerm, EventRepository $eventRepository, Request $request): Response
    {
        return $this->json(["message" => "ok"], 200, [], ['groups' => ['getEvents']]);
    }

}
