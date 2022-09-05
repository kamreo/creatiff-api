<?php


namespace App\Controller;


use App\Controller\Handler\UserRegistrationHandler;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsController]
class RegistrationController extends AbstractController
{
    private UserRegistrationHandler $userRegistrationHandler;
    private UserPasswordHasherInterface $passwordHasher;
    private EntityManagerInterface $em;
    private UserRepository $userRepository;



    public function __invoke(User $data): JsonResponse
    {
        $result = $this->userRegistrationHandler->handle($data, $this->passwordHasher, $this->em, $this->userRepository);
        return $this->json($result[0],$result[1]);
    }
}