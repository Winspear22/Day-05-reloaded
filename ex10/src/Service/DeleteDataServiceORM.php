<?php

namespace App\Service;

use Exception;
use App\Service\UtilsService;
use App\Repository\DataRepository;
use Doctrine\ORM\EntityManagerInterface;

class DeleteDataServiceORM
{
	public function __construct(
		private readonly DataRepository $data_repository,
		private readonly EntityManagerInterface $em,
		private readonly UtilsService $utilsService

	) {}

	public function deleteDataByIdORM(string $tableName, int $id): string
	{
		try
		{
			if (!$this->utilsService->checkTableExistenceORM($tableName))
				return "info: Table $tableName does not exist (ORM).";
			$data = $this->data_repository->find($id);
			if (!$data)
				return "info: Data with ID $id not found.";
			$this->em->remove($data);
			$this->em->flush();
			return "success:Success! Data $id deleted from $tableName.";
		}
		catch (Exception $e)
		{
			return "danger:Error deleting data with ID $id. " . $e->getMessage();
		}
	}

	public function deleteAllDataORM(string $tableName): string
	{
		try
		{
			if (!$this->utilsService->checkTableExistenceORM($tableName))
				return "danger:The table $tableName does not exist (ORM).";
			$datas = $this->data_repository->findAll();
			if (empty($datas))
				return "info:No data to delete.";
			foreach ($datas as $data)
				$this->em->remove($data);
			$this->em->flush();
			return "success:Success! All data deleted from $tableName.";
		}
		catch (Exception $e)
		{
			return "danger:Error deleting all data. " . $e->getMessage();
		}
	}
}