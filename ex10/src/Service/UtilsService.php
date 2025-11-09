<?php

namespace App\Service;

use Exception;
use Doctrine\DBAL\Connection;

class UtilsService
{
	public function __construct(
		private readonly Connection $sql_connection
	) {}

	public function checkTableExistence(string $tableName): bool
	{
		$sql_command = "SHOW TABLES LIKE '$tableName'";
		try
		{
			$result = $this->sql_connection->fetchOne($sql_command);
			return ($result !== false);
		}
		catch (Exception $e)
		{
			return false;
		}
	}
}
?>