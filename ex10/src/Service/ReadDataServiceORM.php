<?php

namespace App\Service;

use Exception;
use App\Service\UtilsService;
use App\Repository\DataRepository;
use Doctrine\ORM\EntityManagerInterface;

class ReadDataServiceORM
{
	public function __construct(
		private readonly DataRepository $data_repository,
		private readonly EntityManagerInterface $em,
		private readonly UtilsService $utilsService

	) {}
	
	public function readAllDataORM(string $tableName): ?array
    {
        try
        {
			if (!$this->utilsService->checkTableExistenceORM($tableName))
				return [];
            return $this->data_repository->findAll();
        }
        catch (Exception $e)
        {
            return [];
        }
    }

	public function readDataORM(string $tableName, int $id)
	{
		try
		{
			if (!$this->utilsService->checkTableExistenceORM($tableName))
				return [];
			return $this->data_repository->find($id);
		}
		catch (Exception $e)
		{
			return [];
		}
	}


}