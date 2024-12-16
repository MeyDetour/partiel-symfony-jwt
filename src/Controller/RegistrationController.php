<?php

namespace App\Controller;

use App\Entity\Profile;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\ProfileRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register', methods: 'post')]
    public function register(Request $request, ProfileRepository $profileRepository, UserPasswordHasherInterface $userPasswordHasher, Security $security, EntityManagerInterface $entityManager, UserRepository $userRepository, SerializerInterface $serializer): Response
    {

        $user = $serializer->deserialize($request->getContent(), User::class, "json");
        $profile = $serializer->deserialize($request->getContent(), Profile::class, "json");

        if (!$profile->getDisplayName() or !$user->getEmail() or !$user->getPassword()){
            return $this->json(["message" => "You must provide email,displayName and password"],400);
        }

        $userExist = $userRepository->findOneBy(['email' => $user->getEmail()]);
        $profileExist = $profileRepository->findOneBy(['displayName' => $profile->getDisplayName()]);

        if ($userExist != null) {
            return $this->json(["message" => "Email already taken"],400);
        }
        if ($profileExist != null) {
            return $this->json(["message" => "Display name already taken"],400);
        }


        $plainPassword = $user->getPassword();
        $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));
        $user->setCreatedAt(new \DateTimeImmutable());
        $entityManager->persist($user);
        $entityManager->flush();

        //add automaticly profile associated
        $profile->setUserAssociated($user);
        $entityManager->persist($profile);
        $entityManager->flush();
        return $this->json(["message" => "ok"], 200);
    }
}
