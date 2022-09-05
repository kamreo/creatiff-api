<?php

namespace App\Controller;

use App\Controller\Handler\FollowHandler;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class FollowController extends AbstractController
{
    private FollowHandler $followHandler;
    private EntityManagerInterface $em;
    private UserRepository $userRepository;



    public function __invoke(Request $request, $id):\Symfony\Component\HttpFoundation\JsonResponse
    {
        $targetUser = $this->em->getRepository(User::class)->find($id);
        $user = $this->getUser();
        $result = $this->followHandler->handle($user, $targetUser, $this->em, $this->userRepository);
        return $this->json($result[0],$result[1]);
    }
}