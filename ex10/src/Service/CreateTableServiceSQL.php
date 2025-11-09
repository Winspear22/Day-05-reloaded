<?php 

namespace App\Service;

use Doctrine\DBAL\Connection;

class CreateTableServiceSQL
{
	public function __construct(
		private readonly Connection $sql_connection,
		private readonly UtilsService $utils_service) {}

	public function createTable(string $tableName): string
	{
		$sql = "CREATE TABLE IF NOT EXISTS $tableName (
			id INT AUTO_INCREMENT PRIMARY KEY,
			data VARCHAR(255) NOT NULL,
			date DATETIME DEFAULT CURRENT_TIMESTAMP
		);";
		try
		{
			if (!$this->utils_service->checkTableExistence($tableName))
				return "info:The table $tableName already exists and cannot be created again.";
			$this->sql_connection->executeStatement($sql);
			return "success:Success! The table $tableName was created!";
		}
		catch (\Exception $e)
		{
			return "danger:Error! The table $tableName could not be created. " . $e->getMessage();
		}
	}
}
?>