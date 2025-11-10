<?php

namespace App\Service;

use Exception;
use App\Entity\Data;
use App\Repository\DataRepository;
use Doctrine\ORM\EntityManagerInterface;

class InsertDataServiceORM
{
	public function __construct(
		private readonly DataRepository $data_repository,
		private readonly EntityManagerInterface $em,
		private readonly UtilsService $utilsService

	) {}

	public function insertDataORM(string $tableName, Data $data): string
	{
		try
		{
			if (!$this->utilsService->checkTableExistenceORM($tableName))
				return "danger: Table $tableName does not exist.";
			$this->em->persist($data);
			$this->em->flush();
			return "success: Data with ID " . $data->getId() . " inserted successfully!";
		}
		catch (Exception $e)
		{
			return "danger: Error inserting data with ID " . $data->getId() . ". " . $e->getMessage();
		}
	}
}