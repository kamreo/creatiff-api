<?php

namespace App\Tests\Unit;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    private User $user;

    protected function setUp(): void
    {
        $user = new User();
        $user->setEmail('example@gmail.com');
        $user->setUsername('test');
        $user->setRoles(["ROLE_USER"]);
        $user->setPassword('testPassword');
        $this->user = $user;
    }

    public function testGetEmail() {
        $this->assertEquals('example@gmail.com', $this->user->getEmail());
    }
}