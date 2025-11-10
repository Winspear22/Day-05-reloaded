<?php

namespace App\Service;

use Exception;
use App\Repository\DataRepository;
use Doctrine\ORM\EntityManagerInterface;

class DeleteDataServiceORM
{
	public function __construct(
		private readonly DataRepository $data_repository,
		private readonly EntityManagerInterface $em

	) {}

	public function deleteDataByIdORM(int $id): string
	{
		try
		{
			$data = $this->data_repository->find($id);
			if (!$data)
				return "info: Data with ID $id not found.";
			$this->em->remove($data);
			$this->em->flush();
			return "success: Data with ID $id deleted successfully!";
		}
		catch (Exception $e)
		{
			return "danger: Error deleting data with ID $id. " . $e->getMessage();
		}
	}

	public function deleteAllDataORM(): string
	{
		try
		{
			$datas = $this->data_repository->findAll();
			if (empty($datas))
				return "info: No data to delete.";
			foreach ($datas as $data)
				$this->em->remove($data);
			$this->em->flush();
			return "success: All data deleted successfully!";
		}
		catch (Exception $e)
		{
			return "danger: Error deleting all data. " . $e->getMessage();
		}
	}
}