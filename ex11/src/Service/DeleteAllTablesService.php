<?php

namespace App\Service;

use Exception;
use Doctrine\DBAL\Connection;
use App\Service\UtilsTableService;

class DeleteAllTablesService
{
	public function __construct(
		private readonly Connection $sql_connection,
		private readonly UtilsTableService $utils_service,
	) {}

	public function deleteTableContent(string $tableName): string
	{
		$sql_command = "DELETE FROM $tableName";
		try
		{
			if (!$this->utils_service->checkTableExistence($tableName))
				return "danger:The table $tableName does not exist (SQL).";
			$this->sql_connection->executeStatement($sql_command);
			return "success:Success! All data deleted from $tableName.";
		}
		catch (Exception $e)
		{
			return "danger:Error while deleting all data: " . $e->getMessage();
		}
	}
}
?>