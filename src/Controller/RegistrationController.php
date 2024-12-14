<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
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
    #[Route('/register', name: 'app_register',methods: 'post')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, Security $security, EntityManagerInterface $entityManager, UserRepository $userRepository , SerializerInterface $serializer): Response
    {

        $user = $serializer->deserialize($request->getContent(),User::class,"json");

        $userExist = $userRepository->findOneBy(['email'=>$user->getEmail()]);
 
        if( $userExist != null ){
            return  $this->json(["message"=>"Email already taken"]);
        }

        $plainPassword = $user->getPassword();
        $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));
        $user->setCreatedAt(new \DateTimeImmutable());
        $entityManager->persist($user);
        $entityManager->flush();
        return $this->json(["message"=>"ok"],200);
    }
}
