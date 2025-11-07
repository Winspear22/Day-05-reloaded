<?php 

namespace App\Service;

use Exception;
use DateTimeInterface;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

class InsertUserInTable 
{
	private readonly Connection $sql_connection;

	public function __construct(Connection $connection)
	{
		$this->sql_connection = $connection;
	}

	public function insertUser(string $tableName, array $data) 
	{
		$sql = "INSERT INTO $tableName (username, name, email, enable, birthdate, address)
		VALUES (:username, :name, :email, :enable, :birthdate, :address)";
		try
		{
			$this->sql_connection->executeStatement($sql, [
				'username' => $data['username'],
				'name' => $data['name'],
				'email' => $data['email'],
				'enable' => $data['enable'],
				'birthdate' => $data['birthdate'] instanceof DateTimeInterface
					? $data['birthdate']->format('Y-m-d H:i:s')
					: $data['birthdate'],
				'address' => $data['address']
			]);
			return "success:Success! User {$data['username']} has been inserted into the table $tableName.";
		}
        catch (UniqueConstraintViolationException $e)
        {
            return "danger:Error - There is a duplicate entry ! Username or email already exists.";
        }
        catch (Exception $e)
        {
            return "danger:Error, there was a problem with the database or the table $tableName : " . $e->getMessage();
        }
	}
}

?>