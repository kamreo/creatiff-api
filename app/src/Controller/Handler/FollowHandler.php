<?php

namespace App\Controller\Handler;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

class FollowHandler
{
    public function handle(User $user, User $targetUser, EntityManagerInterface $em, UserRepository $userRepository){
        try {
            $targetUser->follow($user);
            $em->persist($user);
            $em->persist($targetUser);
            $em->flush();
        } catch(\Exception $e) {
            return ['Failed to follow/unfollow user', Response::HTTP_BAD_REQUEST];
        }
        return ['Successfully followed/unfollowed', Response::HTTP_CREATED];
    }
}