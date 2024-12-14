<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
class PrivateController extends AbstractController
{
    #[Route('/private', name: 'app_private')]
    public function index(): Response
    {
        return $this->json(['message'=>"ok youre connected"],200 );
    }
}
