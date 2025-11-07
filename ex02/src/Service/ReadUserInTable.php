<?php

namespace App\Service;

use Exception;
use Doctrine\DBAL\Connection;

class ReadUserInTable 
{
	private Connection $sql_connection;

	public function __construct(Connection $connection)
	{
		$this->sql_connection = $connection;
	}

	public function readUser(string $tableName, string $username): array|string
	{
		$sql = "SELECT * FROM $tableName WHERE username = :username";
		try
		{
			$user = $this->sql_connection->fetchAssociative($sql, ['username' => $username]);
			if (!$user)
				return "danger:Error - User $username not found in table $tableName.";
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