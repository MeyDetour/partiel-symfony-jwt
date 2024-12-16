<?php

namespace App\Controller;

use App\Entity\Event;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
class ContributionController extends AbstractController
{
    #[Route('/event/{id}/create/contribution', name: 'new_contribution')]
    public function new(Event $event): Response
    {
        if ($event->getOrganisator()!=$this->getUser()->getProfile()){
            return $this->json(["message" => "Only organisator can add contribution"], 400);
        }
        if (!EventController::isValidEvent($event)){
            return $this->json(["message" => "You cannot add contributions to"], 400);

        }
        return $this->json(["message"=>"ok",200]);
    }
}
