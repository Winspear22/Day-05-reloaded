<?php

namespace App\Service;

use Exception;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

class UpdateUserInTable 
{
		public function __construct(
        private readonly EntityManagerInterface $em,
		private readonly UserRepository $repo
	) {}
	
	public function updateUser()
    {
		try
		{
			$this->em->flush();
			return "success: User updated successfully!";
		}
		catch (UniqueConstraintViolationException $e)
		{
			return "danger: Email or username already exists!: " . $e->getMessage();
		}
		catch (Exception $e) 
		{
			return "danger: " . $e->getMessage();
		}
	}
}