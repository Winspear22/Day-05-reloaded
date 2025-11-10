<?php 

namespace App\Service;

use Exception;
use DateTimeInterface;
use Doctrine\DBAL\Connection;

class ReadDataServiceSQL
{
	public function __construct(
		private readonly Connection $sql_connection,
		private readonly UtilsService $utils_service) {}

	public function readAllDataSQL(string $tableName): ?array
	{
        $sql_command = "SELECT * FROM $tableName ORDER BY id ASC";
		try
		{
			
			return $this->sql_connection->fetchAllAssociative($sql_command);
		}
		catch (Exception $e)
		{
			return null;
		}
	}

	public function readDataByIdSQL(string $tableName, int $id): ?array
	{
		$sql_command = "SELECT * FROM $tableName WHERE id = :id";
		try
		{
			$data = $this->sql_connection->fetchAssociative($sql_command, ['id' => $id]);
			if ($data === false)
				return null;
			return $data;
		}
		catch (Exception $e)
		{
			return null;
		}
	}
}
?>