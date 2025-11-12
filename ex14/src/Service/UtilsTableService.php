<?php

namespace App\Service;

use Exception;
use Doctrine\DBAL\Connection;

class UtilsTableService
{

	public function __construct(
		private readonly Connection $sql_connection) {}
	public function checkTableExistence(string $tableName): bool 
	{
		try
		{
			$result = $this->sql_connection->fetchOne("SHOW TABLES LIKE ?", [$tableName]);
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