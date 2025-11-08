<?php

namespace App\Service;

use Exception;
use Doctrine\DBAL\Connection;
use App\Service\UtilsTableService;

class CreatePersonsTableService
{
	public function __construct(
		private readonly Connection $sql_connection,
		private readonly UtilsTableService $utilsTableService) {}

	public function createPersonsTable(string $tableName)
	{
		$sql_command = "CREATE TABLE IF NOT EXISTS $tableName (
			id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
			username VARCHAR(64) NOT NULL UNIQUE,
			name VARCHAR(64) NOT NULL,
			email VARCHAR(128) NOT NULL UNIQUE,
			enable BOOL DEFAULT FALSE NOT NULL,
			birthdate DATETIME NOT NULL)";
		try
		{
			if ($this->utilsTableService->checkTableExistence($tableName))
				return "info:The table $tableName already exists and cannot be created again.";
			$this->sql_connection->executeStatement($sql_command);
            return "success:Success! The table $tableName was created!";
		}
		catch (Exception $e)
		{
			return "danger:Error, there was a problem in the table $tableName creation : " . $e->getMessage();
		}
	}
}

?>