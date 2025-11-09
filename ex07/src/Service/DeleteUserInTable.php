<?php

namespace App\Service;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class DeleteUserInTable 
{
	public function __construct(
        private readonly EntityManagerInterface $em,
		private readonly UserRepository $repo
	) {}

	public function deleteUserById(int $id): string
	{
		try
		{
			$user = $this->repo->find($id);
			if (!$user)
				return "error: User not found.";
			$this->em->remove($user);
			$this->em->flush();
			return "success: User deleted successfully!";
		}
		catch (\Exception $e)
		{
			return "error: " . $e->getMessage();
		}
	}

	public function deleteAllUsers(): string
	{
		try
		{
			$users = $this->repo->findAll();
			if (empty($users))
				return "info: No users to delete.";
			foreach ($users as $user)
				$this->em->remove($user);
			$this->em->flush();
			return "success: All users deleted successfully!";
		}
		catch (\Exception $e)
		{
			return "error: " . $e->getMessage();
		}
	}
}
?>