<?php

namespace App\Tests\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;
use DateTime;

class UserTest extends TestCase
{
    public function testCreateUser(): void
    {
        $user = new User();
        $user->setName('user');
        $user->setEmail('email@gmail.com');
        $user->setPassword('ijrgfeoijgeroiergj');
        $user->setConfirmPassword('ijrgfeoijgeroiergj');
        $date = new DateTime();
        $user->setCreationDate($date);
        $user->setRegistrationToken('goierhgierhghu');
        $user->setStatut(0);

        // Test email format
        $this->assertMatchesRegularExpression("/^[a-zA-Z0-9_.-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/", $user->getEmail());

        // Test confirm password equal password
        $this->assertEquals($user->getPassword(), $user->getConfirmPassword());

        // Test creation date is DateTime
        $this->assertInstanceOf(DateTime::class, $user->getCreationDate());

        // Test statut is boolean
        $this->assertIsBool($user->isStatut());
    }
}
