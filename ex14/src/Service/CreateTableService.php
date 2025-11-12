<?php 

namespace App\Service;

use Exception;
use Doctrine\DBAL\Connection;
use App\Service\UtilsTableService;
use Symfony\Component\Process\Process;

class CreateTableService 
{
	public function __construct(
		private readonly Connection $sql_connection,
		private readonly UtilsTableService $utilsTableService) {}

	public function createPersonsTable(string $tableName): string
	{
        $sql_command = "CREATE TABLE IF NOT EXISTS $tableName (
            id INT AUTO_INCREMENT PRIMARY KEY,
            comment TEXT NOT NULL
		)";
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