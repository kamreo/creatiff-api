<?php


namespace App\Controller\Handler;


use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserRegistrationHandler
{
    /**
     * @return (int|string)[]
     *
     * @psalm-return array{0: 'Couldnt create user'|'Created user successfully'|'User with this email already exists!', 1: 201|400}
     */
    public function handle(User $userData, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $em, UserRepository $userRepository): array{
        try {
            $user = new User();
            $userWithEmail = $userRepository->findBy(['email' => $userData->getEmail()]);

            if ($userWithEmail) {
                return ['User with this email already exists!', Response::HTTP_BAD_REQUEST];
            }

            $user->setEmail($userData->getEmail());
            $user->setUsername($userData->getUsername());
            $user->setRoles($userData->getRoles());

            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $userData->getPassword()
            );

            $user->setPassword($hashedPassword);
            $em->persist($user);
            $em->flush();

        } catch(\Exception $e) {
            return ['Couldnt create user', Response::HTTP_BAD_REQUEST];
        }

        return ['Created user successfully', Response::HTTP_CREATED];
    }
}