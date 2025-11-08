<?php

namespace App\Service;

use Exception;
use Doctrine\DBAL\Connection;
use App\Service\UtilsTableService;

class AlterBankAccountsTableService
{
	public function __construct(
		private readonly Connection $sql_connection,
		private readonly UtilsTableService $utilsTableService) {}

	public function alterBankAccountsTable(): string
	{
		$sql_command = "ALTER TABLE bank_accounts
                    ADD COLUMN person_id INT UNIQUE,
                    ADD CONSTRAINT fk_bank_person FOREIGN KEY (person_id) REFERENCES persons(id) ON DELETE CASCADE";
		$sql_doesForeignKeyExists = "SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE 
                WHERE TABLE_NAME = 'bank_accounts' AND COLUMN_NAME = 'person_id' AND REFERENCED_TABLE_NAME = 'persons'";
		$sql_addForeignKey = "ALTER TABLE bank_accounts
                ADD CONSTRAINT fk_bank_person FOREIGN KEY (person_id) REFERENCES persons(id) ON DELETE CASCADE";
		try
		{
			if (!$this->utilsTableService->checkTableExistence('bank_accounts'))
                return "danger: Error, the table bank_accounts does not exist.";
            if (!$this->utilsTableService->checkTableExistence('persons'))
                return "danger: Error, the table persons does not exist.";
			if (!$this->utilsTableService->doesColumnExist('bank_accounts', 'person_id'))
            {
                $this->sql_connection->executeStatement($sql_command);
                return "success:Success! Relation one-to-one bank_accounts/persons created.";
            }
			$constraints = $this->sql_connection->fetchAllAssociative($sql_doesForeignKeyExists);
			if (!empty($constraints))
                return "info:The relation between bank_accounts and persons already exists.";
            $this->sql_connection->executeStatement($sql_addForeignKey);
            return "success:Success! Foreign key for bank_accounts/persons added.";
		}
		catch (Exception $e)
		{
			return "An error occurred while altering the bank_accounts table: " . $e->getMessage();
		}
	}
}


?>