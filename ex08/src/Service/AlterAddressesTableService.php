<?php

namespace App\Service;

use Exception;
use Doctrine\DBAL\Connection;
use App\Service\UtilsTableService;

class AlterAddressesTableService
{
	public function __construct(
		private readonly Connection $sql_connection,
		private readonly UtilsTableService $utilsTableService) {}
	
	public function alterAddressesTable(string $motherTableName, string $daughterTableName): string
    {
        $sql_command = "ALTER TABLE $daughterTableName
            ADD COLUMN person_id INT NOT NULL,
            ADD CONSTRAINT fk_{$daughterTableName}_{$motherTableName} 
            FOREIGN KEY (person_id) REFERENCES $motherTableName(id) ON DELETE CASCADE";

        $sql_doesForeignKeyExists = "SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE 
            WHERE TABLE_NAME = ? AND COLUMN_NAME = ? AND REFERENCED_TABLE_NAME = ?";
        try
		{
            if (!$this->utilsTableService->checkIfRelationBetweenTablesIsPossible($motherTableName, $daughterTableName))
			{
                $errorMessage = $this->utilsTableService->getRelationErrorMessage($motherTableName, $daughterTableName);
                return $errorMessage;
            }
            $this->sql_connection->executeStatement($sql_command);
            return "success:Relation one-to-many between $daughterTableName and $motherTableName created successfully.";
        }
		catch (Exception $e)
		{
            try
			{
                $constraints = $this->sql_connection->fetchAllAssociative($sql_doesForeignKeyExists, 
                    [$daughterTableName, 'person_id', $motherTableName]
                );
                
                if (!empty($constraints))
                    return "info:The relation between $daughterTableName and $motherTableName already exists.";
            }
			catch (Exception $f) {}
            return "danger:An error occurred while altering the $daughterTableName table: " . $e->getMessage();
        }
    }
}
?>