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

	public function addMaritalStatusToPersons(string $tableName): string
	{
		$sql_command = "ALTER TABLE $tableName ADD COLUMN marital_status ENUM('single', 'married', 'widower') DEFAULT 'single'";
		try
		{
			if (!$this->utilsTableService->checkTableExistence($tableName))
				return "danger: Error, the table $tableName does not exist.";
			if ($this->utilsTableService->doesColumnExist($tableName, 'marital_status'))
				return "info: The column marital_status already exists in table $tableName.";
			$this->sql_connection->executeStatement($sql_command);
			return "success: Success! The column marital_status was added to table $tableName.";
		}
		catch(Exception $e)
		{
			return "danger:Error while altering table $tableName: " . $e->getMessage();
		}
	}

	public function removeMaritalStatusFromPersons(string $tableName): string
	{
		$sql_command = "ALTER TABLE $tableName DROP COLUMN marital_status";
		try
		{
			if (!$this->utilsTableService->checkTableExistence($tableName))
				return "danger: Error, the table $tableName does not exist.";
			if (!$this->utilsTableService->doesColumnExist($tableName, 'marital_status'))
				return "info: The column marital_status does not exist in table $tableName.";
			$this->sql_connection->executeStatement($sql_command);
			return "success: Success! The column marital_status was removed from table $tableName.";
		}
		catch(Exception $e)
		{
			return "danger: Error while altering table $tableName: " . $e->getMessage();
		}
	}
}
?>