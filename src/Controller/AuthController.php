<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PassowrdHasher\Hasher\UserPasswordHaserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class AuthController extends ApiController
{
   #[Route('/register', name: 'api_register', methods: ['POST'])]
   public function register(Request $request, EntityManagerInterface $em, \Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface $passwordHasher)
   {
      $request = $this->transformJsonBody($request);
      $username = $request->get('username');
      $password = $request->get('password');
      if (empty($username) || empty($password)) {
         return $this->respondValidationError('Invalid username or pass word.');
      }

      $user = new User($username);
      $user->setPassword($passwordHasher->hashPassword(
         $user,
         $password
      ));
      $user->setUsername($username);
      $em->persist($user);
      $em->flush();

      return $this->respondWithSuccess(sprintf('User %s successfully created', $user->getUsername()));
   }

   /**
    * @param UserInterface $user
    * @param JWTTokenManagerInterface $JWTManager
    * @param JsonResponse
    */
   public function getTokenUser(UserInterface $user, JWTTokenManagerInterface $JWTManager)
   {
      return new JsonResponse(['token' => $JWTManager->create($user)]);
   }
}
