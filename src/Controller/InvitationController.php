<?php

namespace App\Controller;

use App\Entity\Invitation;
use App\Repository\EventRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api')]
class InvitationController extends AbstractController
{
    #[Route('/invite', name: 'new_inivitation', methods: "post")]
    public function new(Request $request, EventRepository $eventRepository, EntityManagerInterface $manager, UserRepository $userRepository): Response
    {
        //Get data
        $data = json_decode($request->getContent(), true);

        if (!$data['eventId'] and !$data['userId']) {
            return $this->json(["message" => "You must provide eventId and userId"], 404);
        }

        //get user and event and assert that data are correct
        $eventId = $data['eventId'];
        $userId = $data['userId'];
        $event = $eventRepository->find($eventId);
        $user = $userRepository->find($userId);
        if (!$user) {
            return $this->json(["message" => "User found"], 404);
        }
        if ($user->getId() == $this->getUser()->getId()) {
            return $this->json(["message" => "You can not invite yourself"], 404);
        }
        if (!$event) {
            return $this->json(["message" => "Event found"], 404);
        }
        if (!EventController::isValidEvent($event)) {
            return $this->json(["message" => "Event is canceled or passed"], 404);
        }

        if ($event->isPublic()) {
            return $this->json(["message" => "Event is public"], 404);
        }

        //user cannot invite itself
        if ($this->getUser()->getProfile() != $event->getProfile()) {
            # ad administrator permission
            return $this->json(["message" => "Only author can edit this event"], 401);
        }


        $invitation = new Invitation();
        $invitation->setCreatedAt(new \DateTimeImmutable());
        $invitation->setEvent($event);
        $invitation->setGuest($user->getProfile());
        $invitation->setStatus("waiting");
        $manager->persist($invitation);
        $manager->flush();

        return $this->json(["message" => "ok"], 200);

    }
    #create invit
    # edit delete invit

    # accept invit  -> add profile to event
    # if invit already confirm cant accept
    # refuse invit

}
