<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
class UserController extends AbstractController
{
    #[Route('/users', name: 'get_users')]
    public function index(UserRepository $userRepository): Response
    {

        return $this->json(  $userRepository->findAll(),200,[],["groups"=>"users"]);
    }
}
