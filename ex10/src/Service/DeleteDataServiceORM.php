<?php

namespace App\Service;

use Exception;
use App\Service\UtilsService;
use Doctrine\DBAL\Connection;
use App\Service\ReadDataServiceSQL;

class DeleteDataServiceORM
{
	public function __construct(
		private readonly Connection $sql_connection,
		private readonly UtilsService $utils_service,
		private readonly ReadDataServiceSQL $read_service
	) {}

	public function deleteDataByIdORM(string $tableName, int $id)
	{}

	public function deleteAllDataORM(string $tableName) {}

}