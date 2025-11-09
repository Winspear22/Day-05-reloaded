<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use Exception;

class InsertUserInTable
{
    public function __construct(
        private readonly EntityManagerInterface $em
    ) {}

    public function insertUser(User $user): string
    {
        try
		{
            $this->em->persist($user);
            $this->em->flush();
            return 'success: User inserted successfully!';
        } 
		catch (Exception $e)
		{
            if (str_contains($e->getMessage(), 'Unique constraint failed'))
                return 'error: Email or username already exists!';
            return 'error: ' . $e->getMessage();
        }
    }
}