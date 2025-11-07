<?php

namespace App\Service;

use Exception;
use DateTimeInterface;
use Doctrine\DBAL\Connection;
use App\Service\DeleteUserInTable;

class UpdateUserInTable 
{
	private readonly Connection $sql_connection;
	public function __construct(
		Connection $connection,
		private readonly DeleteUserInTable $deleteService)
	{
		$this->sql_connection = $connection;
	}

	public function updateUser(string $tableName, int $id, array $data)	: string
	{
		$sql = "UPDATE $tableName SET 
			username = :username, 
			name = :name, 
			email = :email, 
			enable = :enable, 
			birthdate = :birthdate, 
			address = :address 
			WHERE id = :id";
		try
		{
			if ($this->deleteService->checkIfUserExistsById($tableName, $id))
			{
				$this->sql_connection->executeStatement($sql, [
					'username' => $data['username'],
					'name' => $data['name'],
					'email' => $data['email'],
					'enable'    => $data['enable'] ? 1 : 0,
					'birthdate' => $data['birthdate'] instanceof DateTimeInterface
						? $data['birthdate']->format('Y-m-d H:i:s')
						: $data['birthdate'],
					'address' => $data['address'],
					'id' => $id
				]);
				return "success:Success! User with ID $id has been updated in the table $tableName.";
			}
			else
				return "danger:Error - User with ID $id does not exist in the table $tableName.";
		}
		catch (Exception $e)
		{
			return "danger:Error, there was a problem updating the user with ID $id in the table $tableName : " . $e->getMessage();
		}
	}
}


?>