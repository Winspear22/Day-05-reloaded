<?php 

namespace App\Service;

use Exception;
use DateTimeInterface;
use Doctrine\DBAL\Connection;

class InsertDataServiceSQL
{
	public function __construct(
		private readonly Connection $sql_connection,
		private readonly UtilsService $utilsService) {}

	public function insertDataSQL(string $tableName, string $data, DateTimeInterface $date): string
	{
		$sql_command = "INSERT INTO $tableName (data, date) VALUES (:data, :date);";
		try
		{
			if (!$this->utilsService->checkTableExistenceSQL($tableName))
				return "danger: Table $tableName does not exist.";
			$this->sql_connection->executeStatement($sql_command, [
				'data' => $data,
				'date' => $date->format('Y-m-d H:i:s')
			]);
			return "success:Success! Data inserted into $tableName.";
		}
		catch (Exception $e)
		{
			return "danger:Error! Data could not be inserted into $tableName. " . $e->getMessage();
		}
	}
}
?>