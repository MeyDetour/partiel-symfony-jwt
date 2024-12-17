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
                'body' => [],
                'sendBack' => [
                    "id" => "int (AI) ",
                    "email" => "string",
                    "createdAt" => "string (d.m.Y h:i)",
                    "profile" => [
                        "id" => "int (AI) ",
                        "displayName" => "string",
                        "imageUrl" => "string"
                    ], ["..."]
                ],
                'token' => true
            ], [
                'name' => 'Get own profile',
                'route' => '/api/profile',
                'methode' => 'GET',
                'body' => [],
                'sendBack' => [
                    [
                        "id" => "int (AI)",
                        "displayName" => "string",
                        "imageUrl" => "string",
                        "userAssociated" => [
                            "id" => "int (AI) ",
                            "email" => "string",
                            "createdAt" => "string (d.m.Y h:i)",
                        ], ['...']
                    ]
                ],
                'token' => true
            ], [
                'name' => 'Edit own profile',
                'route' => '/api/profile',
                'methode' => 'PUT',
                'body' => ["displayName" => "string (NOT NULL)",
                ],
                'sendBack' =>
                    ["message" => "ok"
                    ],
                'token' => true
            ], [
                'name' => 'Delete profile',
                'route' => '/api/profile',
                'methode' => 'DELETE',
                'body' => [],
                'sendBack' =>
                    ["message" => "ok"],
                'token' => true
            ], [
                'name' => 'Upload profile image',
                'route' => '/api/profile/upload/image',
                'methode' => 'POST',
                'body' => ["formdata" => "form data with key 'image'"],
                'sendBack' => [
                    "url" => "string"
                ],
                'token' => true
            ], [
                'name' => 'Get events wich we are in',
                'route' => '/api/profile/events',
                'methode' => 'GET',
                'body' => [],
                'sendBack' => [
                    [
                        "id" => "int (AI) (NOT NULL)",
                        "name" => "string",
                        "description" => "string",
                        "isPublic" => "boolean",
                        "startDate" => "string (d.m.Y h:i)",
                        "endDate" => "string (d.m.Y h:i)",
                        "isPublicPlace" => "boolean",
                        "state" => "onSchedule/canceled",
                        "createdAt" => "string (d.m.Y h:i)"
                    ], ['...']
                ],
                'token' => true
            ], [
                'name' => 'Get our contributions',
                'route' => '/api/profile/contributions',
                'methode' => 'GET',
                'body' => [],
                'sendBack' => [
                    [
                        "id" => "int (AI) (NOT NULL)",
                        "name" => "string",
                        "description" => "string",
                        "isPublic" => "boolean",
                        "startDate" => "string (d.m.Y h:i)",
                        "endDate" => "string (d.m.Y h:i)",
                        "isPublicPlace" => "boolean",
                        "state" => "onSchedule/canceled",
                        "createdAt" => "string (d.m.Y h:i)"
                    ], ['...']
                ],
                'token' => true
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
