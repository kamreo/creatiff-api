<?php

namespace App\Controller\Handler;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

class FollowHandler
{
    /**
     * @return (int|string)[]
     *
     * @psalm-return array{0: 'Failed to follow/unfollow user'|'Successfully followed/unfollowed', 1: 201|400}
     */
    public function handle(User $user, User $targetUser, EntityManagerInterface $em, UserRepository $userRepository): array{
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