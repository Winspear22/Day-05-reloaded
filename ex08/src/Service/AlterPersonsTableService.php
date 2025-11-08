<?php

namespace App\Service;

use Exception;
use Doctrine\DBAL\Connection;
use App\Service\UtilsTableService;

class AlterPersonsTableService
{
	public function __construct(
		private readonly Connection $sql_connection,
		private readonly UtilsTableService $utilsTableService) {}

	public function addColumn(string $tableName, string $columnName, string $columnType): string
	{
		$sql = "ALTER TABLE $tableName ADD COLUMN $columnName $columnType";
		try
		{
			if (!$this->checkTableExistence($tableName))
				return "info:The table $tableName does not exist.";
			$this->sql_connection->executeStatement($sql);
			return "success:Success! The column $columnName was added to the table $tableName!";
		}
		catch (Exception $e)
		{
			return "danger:Error, there was a problem in the column $columnName addition : " . $e->getMessage();
		}
	}

	public function removeColumn(string $tableName, string $columnName): string
	{
		$sql = "ALTER TABLE $tableName DROP COLUMN $columnName";
		try
		{
			if (!$this->checkTableExistence($tableName))
				return "info:The table $tableName does not exist.";
			$this->sql_connection->executeStatement($sql);
			return "success:Success! The column $columnName was removed from the table $tableName!";
		}
		catch (Exception $e)
		{
			return "danger:Error, there was a problem in the column $columnName removal : " . $e->getMessage();
		}
	}

	public function checkTableExistence(string $tableName): bool
	{
		try
		{
			$result = $this->sql_connection->fetchOne("SHOW TABLES LIKE '$tableName'");
			if ($result === false)
				return false;
		}
		catch (Exception $e)
		{
			return false;
		}
		return true;
	}
}


?>