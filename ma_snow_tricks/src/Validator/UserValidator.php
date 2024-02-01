<?php

// src/Validator/UserValidator.php
namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class UserValidator extends ConstraintValidator
{
    /**
     * @param User $user
     */
    public function validate($user, Constraint $constraint): void
    {
        if (!$user instanceof user) {
            throw new UnexpectedValueException($user, User::class);
        }
    }
}