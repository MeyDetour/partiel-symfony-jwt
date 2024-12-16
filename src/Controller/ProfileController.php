<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(): Response
    {
        return $this->json($this->getUser()->getProfile(), 200, [], ['groups' => 'profile']);
    }

    #[Route('/profile/invitations', name: 'app_profile_invitations',methods: 'get')]
    public function getInvitations(): Response
    {
        $invitations = [];
        foreach ($this->getUser()->getProfile()->getInvitations() as $invitation) {
            $event = $invitation->getEvent();
            if (EventController::isValidEvent($event)) {
                $invitations[] = $invitation;
            }
        }

        return $this->json($invitations, 200, [], ['groups' => 'invitations']);
    }
}
