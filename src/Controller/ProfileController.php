<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Profile;
use App\Repository\ProfileRepository;
use App\Service\ImageService;
use App\Service\ValidatorService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api')]
class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile', methods: "get")]
    public function index(ImageService $imageService): Response
    {
        $profile = $this->getUser()->getProfile();
        if ($profile->getImage()) {
            $imageUrl = $imageService->getImageUrl($this->getUser()->getProfile()->getImage(), "profile_image");
            $profile->setImageUrl($imageUrl);
        }
        return $this->json($profile, 200, [], ['groups' => 'profile']);
    }

    #[Route('/profile', name: 'edit_profile', methods: 'put')]
    public function edit(SerializerInterface $serializer, EntityManagerInterface $manager, Request $request, ProfileRepository $profileRepository): Response
    {
        $profileEdited = $serializer->deserialize($request->getContent(), Profile::class, 'json');
        if (trim($profileEdited->getdisplayName(), ' ') == "") {
            return $this->json(["message" => "You must provide valid displayName"], 400);
        }
        $profileExist = $profileRepository->findOneBy(['displayName' => $profileEdited->getDisplayName()]);
        if ($profileExist) {
            return $this->json(["message" => "Display name already taken"], 400);
        }
        $this->getUser()->getProfile()->setDisplayName($profileEdited->getDisplayName());
        $manager->persist($this->getUser()->getProfile());
        $manager->flush();

        return $this->json($this->getUser()->getProfile(), 200, [], ['groups' => 'profile']);
    }

    #[Route('/profile', name: 'delete_profile', methods: 'delete')]
    public function delete(SerializerInterface $serializer, EntityManagerInterface $manager, Request $request, ProfileRepository $profileRepository): Response
    {
        $manager->remove($this->getUser());
        $manager->flush();
        return $this->json(["message" => "ok"], 200, [], ['groups' => 'profile']);
    }

    #[Route('/profile/invitations', name: 'app_profile_invitations', methods: 'get')]
    public function getInvitations(): Response
    {
        $invitations = [];
        foreach ($this->getUser()->getProfile()->getInvitations() as $invitation) {
            $event = $invitation->getEvent();
            if (ValidatorService::isValidEvent($event)) {
                $invitations[] = $invitation;
            }
        }
        return $this->json($invitations, 200, [], ['groups' => 'invitations']);
    }

    #[Route('/profile/upload/image', name: 'app_profile_upload_image', methods: 'post')]
    public function uploadImage(Request $request, EntityManagerInterface $manager, ImageService $imageService): Response
    {
        $file = $request->files->get('image');

        if (!$file) {
            return $this->json(["message" => "You must provide an image"], 400);
        }
        $image = new Image();
        $image->setImageFile($file);
        $image->setProfile($this->getUser()->getProfile());
        $manager->persist($image);
        $manager->flush();

        $imageUrl = $imageService->getImageUrl($image, "profile_image");

        return $this->json(["url" => $imageUrl], 201, []);
    }

    #[Route('/profile/events', name: 'app_profile_events', methods: 'get')]
    public function getEventsWichWeAreParticipant(): Response
    {
        $events = [];
        foreach ($this->getUser()->getProfile()->getEventsWichProfileParticip() as $event) {
            if (ValidatorService::isValidEvent($event)) {
                $events[] = $event;
            }
        }
        return $this->json($events, 200, [], ['groups' => 'getEvents']);
    }

    #[Route('/profile/contributions', name: 'app_profile_contributions', methods: 'get')]
    public function getContributionsForProfile(): Response
    {
        $contributions = [];
        foreach ($this->getUser()->getProfile()->getContributions() as $contribution) {
            if (ValidatorService::isValidEvent($contribution->getEvent())) {
                $contributions[] = $contribution;
            }
        }
        return $this->json($contributions, 200, [], ['groups' => 'contributionsProfile']);
    }

}