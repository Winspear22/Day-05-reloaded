<?php 

namespace App\Service;

use Exception;
use RuntimeException;
use Doctrine\DBAL\Connection;

class CreateTableService 
{
	private readonly Connection $sql_connection;

	public function __construct(Connection $connection) 
	{
		$this->sql_connection = $connection;
	}

	public function createTable(string $tableName): string
	{
		$sql_command = "CREATE TABLE IF NOT EXISTS $tableName (
			id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
			username VARCHAR(64) NOT NULL UNIQUE,
			name VARCHAR(64) NOT NULL,
			email VARCHAR(128) NOT NULL UNIQUE,
			enable BOOL DEFAULT FALSE NOT NULL,
			birthdate DATETIME NOT NULL,
			address LONGTEXT NOT NULL
		)";
		try
		{
			$result = $this->checkTableExistence($tableName);
			if ($result === true)
				return "info: Table already exists.";
			$this->sql_connection->executeStatement($sql_command);
			return "success: Table created successfully.";
		}
		catch (Exception $e)
		{
			throw new RuntimeException("Failed to create table: " . $e->getMessage());
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