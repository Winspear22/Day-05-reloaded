<?php

namespace App\Service;

use Exception;
use App\Service\UtilsService;
use Doctrine\DBAL\Connection;
use App\Service\ReadDataServiceSQL;

class DeleteDataServiceSQL
{
	public function __construct(
		private readonly Connection $sql_connection,
		private readonly UtilsService $utils_service,
		private readonly ReadDataServiceSQL $read_service
	) {}

	public function deleteDataByIdSQL(string $tableName, int $id): string
	{
		$sql_command = "DELETE FROM $tableName WHERE id = :id";
		try
		{
			if (!$this->utils_service->checkTableExistenceSQL($tableName))
				return "info: The table $tableName does not exist.";
			if ($this->read_service->readDataByIdSQL($tableName, $id) === null)
				return "danger:Error, data $id does not exist.";
			$this->sql_connection->executeStatement($sql_command, ['id' => $id]);
			return "success:Success! Data $id deleted from $tableName.";
		}
		catch (Exception $e)
		{
            return "danger:Error while deleting data $id: " . $e->getMessage();
		}
	}

	public function deleteAllDataSQL(string $tableName): string
	{
		$sql_command = "DELETE FROM $tableName";
		try
		{
			if ($this->utils_service->checkTableExistenceSQL($tableName))
				return "info: The table $tableName does not exist (SQL).";
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