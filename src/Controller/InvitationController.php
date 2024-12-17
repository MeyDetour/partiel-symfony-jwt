<?php

namespace App\Controller;

use App\Entity\Invitation;
use App\Repository\EventRepository;
use App\Repository\UserRepository;
use App\Service\ValidatorService;
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
        if (!ValidatorService::isValidEvent($event)) {
            return $this->json(["message" => "Event is canceled or passed"], 404);
        }

        if ($event->isPublic()) {
            return $this->json(["message" => "Event is public"], 404);
        }

        // profile must be organisator or administrator
        if ($this->getUser()->getProfile() != $event->getOrganisator() and !$event->isProfileInAdministrators($this->getUser()->getProfile() )) {
            return $this->json(["message" => "Only author and administrators can do this action"], 401);
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

    #[Route('/invite/{id}/accept', name: 'accept_invitation', methods: "patch")]
    public function accept(EntityManagerInterface $manager, Invitation $invitation): Response
    {
        //we dont need to assert if invite is refused because we cant change choice evrybody can do mistake

        //other people can not accept your invitation (logic lol)
        if ($this->getUser()->getProfile() != $invitation->getGuest()) {
            return $this->json(["message" => "It's not your invitation"], 404);
        }

        $event = $invitation->getEvent();

        //if event is passed we set invitation as refused
        $now = new \DateTime();
        if (!$event->getStartDate() > $now) {
            $invitation->setStatus("refused");
            $invitation->setStatus("refused");
            $event->removeParticipant($this->getUser()->getProfile());
            $manager->persist($invitation);
            $manager->flush();
            return $this->json(["message" => "Event is passed"], 404);
        }

        //we can do nothing if event is canceled, maybe organisator will reactivate event
        if ($event->getState() == "onSchedule"){
            return $this->json(["message" => "Event is canceled"], 404);
        }


        $invitation->setStatus("accepted");
        $event->addParticipant($this->getUser()->getProfile());
        $manager->persist($invitation);
        $manager->persist($event);
        $manager->flush();
        return $this->json(["message" => "ok"], 200);

    }

    #[Route('/invite/{id}/refuse', name: 'arefuse_invitation', methods: "patch")]
    public function refuse(EntityManagerInterface $manager, Invitation $invitation): Response
    {
        //we dont need to assert if invite is refused because we cant change choice evrybody can do mistake


        //other people can not accept your invitation (logic lol)
        if ($this->getUser()->getProfile() != $invitation->getGuest()) {
            return $this->json(["message" => "It's not your invitation"], 404);
        }

        $event = $invitation->getEvent();

        //if event is passed we set invitation as refused
        $now = new \DateTime();
        if (!$event->getStartDate() > $now) {
          $invitation->setStatus("refused");
            $invitation->setStatus("refused");
            $event->removeParticipant($this->getUser()->getProfile());
            $manager->persist($invitation);
            $manager->flush();
            return $this->json(["message" => "Event is passed"], 404);
        }

        //we can do nothing if event is canceled, maybe organisator will reactivate event
        if ($event->getState() == "onSchedule"){
            return $this->json(["message" => "Event is canceled"], 404);
        }

        $invitation->setStatus("refused");
        $event->removeParticipant($this->getUser()->getProfile());
        $manager->persist($invitation);
        $manager->persist($event);
        $manager->flush();
        return $this->json(["message" => "ok"], 200);

    }

}
