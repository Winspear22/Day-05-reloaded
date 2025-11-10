<?php

namespace App\Service;

use Exception;
use Doctrine\DBAL\Connection;

class UtilsService
{
	public function __construct(
		private readonly Connection $connection,
	) {}

	public function checkTableExistenceSQL(string $tableName): bool
	{
		$sql_command = "SHOW TABLES LIKE '$tableName'";
		try
		{
			$result = $this->connection->fetchOne($sql_command);
			return ($result !== false);
		}
		catch (Exception $e)
		{
			return false;
		}
	}

	public function checkTableExistenceORM(string $tableName): bool
	{
		try
		{
			$schemaManager = $this->connection->createSchemaManager();
			//if (empty($schemaManager))
			//	return false;
			//return true;
			return $schemaManager->tablesExist([$tableName]);
		}
		catch (Exception $e)
		{
			return false;
		}
	}
}
?>