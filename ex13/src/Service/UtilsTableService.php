<?php 

namespace App\Service;

use Exception;
use Doctrine\DBAL\Connection;

class UtilsTableService 
{
	public function __construct(
		private readonly Connection $orm_connection
	) {}

	public function checkTableExistence(string $tableName): bool
	{
		try
		{
			$schemaManager = $this->orm_connection->createSchemaManager();
			return $schemaManager->tablesExist([$tableName]);
		}
		catch (Exception $e)
		{
			return false;
		}
	}
}