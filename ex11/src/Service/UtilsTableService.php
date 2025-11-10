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

	public function doesColumnExist(string $tableName, string $columnName): bool
	{
		try
		{
			$columns = $this->sql_connection->fetchAllAssociative("SHOW COLUMNS FROM $tableName LIKE :column", ['column' => $columnName]);
			return count($columns) > 0;
		}
		catch (Exception $e)
		{
			return false;
		}
	}

	public function checkIfRelationBetweenTablesIsPossible(string $motherTableName, string $daughterTableName): bool
	{
		if (!$this->checkTableExistence($daughterTableName))
            return false;
        if (!$this->checkTableExistence($motherTableName))
            return false;
		if ($this->doesColumnExist($daughterTableName, 'person_id'))
			return false;
		return true;
	}
	
	public function getRelationErrorMessage(string $motherTableName, string $daughterTableName): string
    {
        if (!$this->checkTableExistence($daughterTableName))
            return "danger:Error, the table $daughterTableName does not exist.";
        if (!$this->checkTableExistence($motherTableName))
            return "danger:Error, the table $motherTableName does not exist.";
        if ($this->doesColumnExist($daughterTableName, 'person_id'))
            return "danger:Error! Relation between $daughterTableName and $motherTableName already exists.";
        return "";
    }
}
?>