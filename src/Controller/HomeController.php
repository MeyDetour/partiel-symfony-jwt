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
                        "description" => "null because contirbution is attached to sughgestion",
                        "suggestion" => [
                            "id" => "int (AI) (NOT NULL)",
                            "description" => "string",
                            "isTaken" => "boolean"
                        ],
                        "event" => [
                            "id" => "int (AI) (NOT NULL)",
                            "name" => "string",
                            "isPublic" => "boolean",
                            "startDate" => "string (d.m.Y h:i)",
                            "endDate" => "string (d.m.Y h:i)",
                            "description" => "string",
                            "isPublicPlace" => "boolean",
                            "state" => "onSchedule/canceled",
                            "createdAt" => "string (d.m.Y h:i)"
                        ]
                    ],
                    ["id" => "int (AI) (NOT NULL)",
                        "description" => "string (NOT NULL)",
                        "suggestion" => "NULL",
                        "event" => [
                            "id" => "int (AI) (NOT NULL)",
                            "name" => "string",
                            "isPublic" => "boolean",
                            "startDate" => "string (d.m.Y h:i)",
                            "endDate" => "string (d.m.Y h:i)",
                            "description" => "string",
                            "isPublicPlace" => "boolean",
                            "state" => "onSchedule/canceled",
                            "createdAt" => "string (d.m.Y h:i)"
                        ]]
                    , ['...']
                ],
                [
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
                ],
            ]


        ];
        $eventRoute = [
            [
                'name' => 'Create Event',
                'route' => '/api/create/event',
                'methode' => 'GET',
                'body' => [
                    "name" => "create by max",
                    "description" => "string",
                    "startDate" => "17.12.2024 9=>50",
                    "endDate" => "18.12.2024 10=>43",
                    "publicPlace" => true,
                    "public" => false
                ],
                'sendBack' => [

                ],
                'token' => true
            ], [
                'name' => 'Edit Event',
                'route' => '/api/edit/event/{id}',
                'methode' => 'PUT',
                'body' => ["name" => "create by max",
                    "description" => "string",
                    "startDate" => "17.12.2024 9=>50",
                    "endDate" => "18.12.2024 10=>43",
                    "publicPlace" => true,
                    "public" => false],
                'sendBack' => [

                ],
                'token' => true
            ], [
                'name' => 'Get Events',
                'route' => '/api/events',
                'methode' => 'GET',
                'body' => [],
                'sendBack' => [
                    [
                        "id" => "int (AI) (NOT NULL)",
                        "name" => "string",
                        "isPublic" => "boolean",
                        "startDate" => "string (d.m.Y h:i)",
                        "endDate" => "string (d.m.Y h:i)",
                        "description" => "string",
                        "isPublicPlace" => "boolean",
                        "state" => "onSchedule/canceled",
                        "createdAt" => "string (d.m.Y h:i)"
                    ], ["..."]
                ],
                'token' => true
            ], [
                'name' => 'Get Events Private',
                'route' => '/api/event/private',
                'methode' => 'GET',
                'body' => [],
                'sendBack' => [
                    [
                        "id" => "int (AI) (NOT NULL)",
                        "name" => "string",
                        "isPublic" => "boolean",
                        "startDate" => "string (d.m.Y h:i)",
                        "endDate" => "string (d.m.Y h:i)",
                        "description" => "string",
                        "isPublicPlace" => "boolean",
                        "state" => "onSchedule/canceled",
                        "createdAt" => "string (d.m.Y h:i)"
                    ], ["..."]
                ],
                'token' => true
            ], [
                'name' => 'Get Events Public',
                'route' => '/api/event/public',
                'methode' => 'GET',
                'body' => [],
                'sendBack' => [
                    [
                        "id" => "int (AI) (NOT NULL)",
                        "name" => "string",
                        "isPublic" => "boolean",
                        "startDate" => "string (d.m.Y h:i)",
                        "endDate" => "string (d.m.Y h:i)",
                        "description" => "string",
                        "isPublicPlace" => "boolean",
                        "state" => "onSchedule/canceled",
                        "createdAt" => "string (d.m.Y h:i)"
                    ], ["..."]
                ],
                'token' => true
            ], [
                'name' => 'Get one event',
                'route' => '/api/event/{id}',
                'methode' => 'GET',
                'body' => [],
                'sendBack' => [

                    "id" => "int (AI) (NOT NULL)",
                    "name" => "string",
                    "isPublic" => "boolean",
                    "startDate" => "string (d.m.Y h:i)",
                    "endDate" => "string (d.m.Y h:i)",
                    "description" => "string",
                    "isPublicPlace" => "boolean",
                    "state" => "onSchedule/canceled",
                    "createdAt" => "string (d.m.Y h:i)",
                    "participants" => [
                        ["id" => "int (AI) (NOT NULL)",
                            "displayName" => "string"
                        ]
                    ],
                    "invitations" => [],
                    "suggestions" => [
                        [
                            "id" => "int (AI) (NOT NULL)",
                            "description" => "string",
                            "isTaken" => true
                        ]
                    ],
                    "contributions" => [
                        [
                            "id" => "int (AI) (NOT NULL)",
                            "description" => null,
                            "author" => [
                                "id" => 5,
                                "displayName" => "MeïMeï"
                            ],
                            "suggestionId" => 2
                        ],
                        [
                            "id" => "int (AI) (NOT NULL)",
                            "description" => "string",
                            "author" => [
                                "id" => "int (AI) (NOT NULL)",
                                "displayName" => "MeïMeï"
                            ],
                            "suggestionId" => null
                        ]
                    ],
                    "administrators" => []
                ],
                'token' => true
            ], [
                'name' => 'Search event',
                'route' => '/api/search/Event/{searchterm}',
                'methode' => 'GET',
                'body' => [],
                'sendBack' => [
                    [
                        "id" => "int (AI) (NOT NULL)",
                        "name" => "string",
                        "isPublic" => "boolean",
                        "startDate" => "string (d.m.Y h:i)",
                        "endDate" => "string (d.m.Y h:i)",
                        "description" => "string",
                        "isPublicPlace" => "boolean",
                        "state" => "onSchedule/canceled",
                        "createdAt" => "string (d.m.Y h:i)"
                    ], ["..."]
                ],
                'token' => true
            ], [
                'name' => 'Add administrator',
                'route' => '/api/event/{event id}/add/profile/{profile id}/as/administrator',
                'methode' => 'PATCH',
                'body' => [],
                'sendBack' => [
                ],
                'token' => true
            ], [
                'name' => 'Add administrator',
                'route' => '/api/event/{event id}/remove/profile/{profile id }/from/administrators',
                'methode' => 'DELETE',
                'body' => [],
                'sendBack' => [
                ],
                'token' => true
            ], [
                'name' => 'Join public event',
                'route' => '/api/join/event/{id event }',
                'methode' => 'PATCH',
                'body' => [],
                'sendBack' => [
                ],
                'token' => true
            ], [
                'name' => 'Left public event',
                'route' => '/api/left/event/{id event }',
                'methode' => 'PATCH',
                'body' => [],
                'sendBack' => [
                ],
                'token' => true
            ], [
                'name' => 'Close/canceled public event',
                'route' => '/api/event/{id event }',
                'methode' => 'PATCH',
                'body' => [],
                'sendBack' => [
                ],
                'token' => true
            ],
        ];
        $invitationRoute = [
            [
                'name' => 'Invite to private event',
                'route' => '/api/invite',
                'methode' => 'POST',
                'body' => [
                    "eventId" => 20,
                    "profileId" => 16
                ],
                'sendBack' => [
                ],
                'token' => true
            ],
        ];
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
