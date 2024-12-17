<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        $userRoute = [
            [
                'name' => 'Register',
                'route' => '/register',
                'methode' => 'POST',
                'body' => [
                    ["displayName" => "string (NOT NUL)",
                        "email" => "string (NOT NUL)",
                        "password" => "string (NOT NUL)"
                    ]
                ],
                'sendBack' => ["message" => "ok"],
                'token' => false
            ],
            [
                'name' => 'Login',
                'route' => '/api/login_check',
                'methode' => 'POST',
                'body' => [
                    [
                        "username" => "string (NOT NUL)",
                        "password" => "string (NOT NUL)"
                    ]
                ],
                'sendBack' => ["token" => "string"],
                'token' => false
            ],
            [
                'name' => 'Get all users',
                'route' => '/api/users',
                'methode' => 'GET',
                'body' => [  ],
                'sendBack' =>  [
                    "id" => "int (AI) ",
                    "email" => "string",
                    "createdAt" => "string (d.m.Y h:i)",
                    "profile" => [
                        "id" =>"int (AI) ",
                        "displayName" => "string"
                    ],["..."]
                ],
                'token' => false
            ]


        ];
        $eventRoute = [];
        $invitationRoute = [];
        $suggestionRoute = [];

        /*
        [
                'name' => 'Register',
                'route' => '/register',
                'methode' => 'POST',
                'body' => [
                    ["displayName" => "string",
                        "email" => "string",
                        "password" => "string"
                    ]
                ],
                'sendBack' => ["message"=>"ok"],
                'token' => true
            ]
        */

        $themes = [
            "User" => $userRoute,
            "Event" => $eventRoute,
            "Invitation" => $invitationRoute,
            "suggestion" => $suggestionRoute
        ];

        return $this->render('home/index.html.twig', [
            'themes' => $themes,
        ]);
    }
}
