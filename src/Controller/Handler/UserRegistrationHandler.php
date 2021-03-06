<?php


namespace App\Controller\Handler;


use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserRegistrationHandler
{
    public function handle(User $userData, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $em, UserRepository $userRepository){
        try{
            $user = new User();
            $userWithEmail = $userRepository->findBy(['email' => $userData->getEmail()]);

            if ($userWithEmail) {
                return ['User with this email already exists!', Response::HTTP_BAD_REQUEST];
            }

            $user->setEmail($userData->getEmail());
            $user->setNickname($userData->getNickname());
            $user->setRoles([]);

            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $userData->getPassword()
            );

            $user->setPassword($hashedPassword);
            $em->persist($user);
            $em->flush();

        }catch(\Exception $e){
            return ['Couldnt create user', Response::HTTP_BAD_REQUEST];        }

        return ['Created user successfully', Response::HTTP_CREATED];
    }
}