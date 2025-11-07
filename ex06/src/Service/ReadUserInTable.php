<?php

namespace App\Service;

use Exception;
use Doctrine\DBAL\Connection;

class ReadUserInTable 
{
	private readonly Connection $sql_connection;

	public function __construct(Connection $connection)
	{
		$this->sql_connection = $connection;
	}

	public function readUser(string $tableName, int $id): array|string
	{
		$sql = "SELECT * FROM $tableName WHERE id = ?";	
		try
		{
			$user = $this->sql_connection->fetchAssociative($sql, [$id]);
			if (!$user)
				return "danger:Error - User with ID $id not found in table $tableName.";
			return $user;
		}
		catch (Exception $e)
		{
			return "danger:Error, there was a problem with the database or the table $tableName : " . $e->getMessage();
		}
	}

	public function readAllUsers(string $tableName): array
	{
		$sql = "SELECT * FROM $tableName";
		try
		{
			return $this->sql_connection->fetchAllAssociative($sql) ?? [];
		}
		catch (Exception $e)
		{
			return [];
		}
	}
}

?>