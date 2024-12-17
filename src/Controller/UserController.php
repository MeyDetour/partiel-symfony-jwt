<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Service\ImageService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
class UserController extends AbstractController
{
    #[Route('/users', name: 'get_users')]
    public function index(UserRepository $userRepository, ImageService $imageService): Response
    {
        $users = $userRepository->findAll();
        foreach ($users as $user){
            $profile = $user->getProfile();
            if ($profile->getImage()) {
                $imageUrl = $imageService->getImageUrl($profile->getImage(), "profile_image");
                $profile->setImageUrl($imageUrl);
            }
        }



        return $this->json( $users ,200,[],["groups"=>"users"]);
    }
}
