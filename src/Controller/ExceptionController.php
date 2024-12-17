<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;

class ExceptionController extends AbstractController
{
    public function notFound(NotFoundHttpException $exception): Response
    {
        return $this->json(
            ['message' => "Entity not found",], 400
        );
    }
}