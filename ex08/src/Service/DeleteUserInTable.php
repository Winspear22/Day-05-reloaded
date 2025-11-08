<?php 

namespace App\Service;

use Exception;
use Doctrine\DBAL\Connection;

class DeleteUserInTable
{
	private readonly Connection $sql_connection;

	public function __construct(Connection $connection)
	{
		$this->sql_connection = $connection;
	}

	public function deleteUser(string $tableName, int $id) 
	{
		$sql = "DELETE FROM $tableName WHERE id = ?";
		try
		{
			if (!$this->checkIfUserExistsById($tableName, $id))
                return "danger:Error - User with ID $id does not exist in the table $tableName.";
			$this->sql_connection->executeStatement($sql, [$id]);
			return "success:Success! User with ID $id has been deleted from the table $tableName.";
		}
		catch (Exception $e)
		{
			return "danger:Error, there was a problem checking the existence of user with ID $id in the table $tableName : " . $e->getMessage();
		}
	}

	public function deleteAllUsers(string $tableName) 
	{
		$sql = "DELETE FROM $tableName";
		try
		{
			$count = $this->getUserCount($tableName);
            if ($count <= 0)
                return "danger:Error - There are no users to delete in the table $tableName.";
			$this->sql_connection->executeStatement($sql);
			return "success:Success! All users have been deleted from the table $tableName.";
		}
		catch (Exception $e)
		{
			return "danger:Error, Error while deleting all users in the $tableName : " . $e->getMessage();
		}
	}

    public function checkIfUserExistsById(string $tableName, int $id): bool
    {
		$sql = "SELECT id FROM $tableName WHERE id = ?";
        try
        {
            $result = $this->sql_connection->fetchOne($sql, [$id]);
            return $result !== false;
        }
        catch (Exception $e)
        {
            return false;
        }
    }

	public function getUserCount(string $tableName): int
    {
		$sql = "SELECT COUNT(*) as count FROM $tableName";
        try
        {
            $result = $this->sql_connection->fetchOne($sql);
            return (int)$result;
        }
        catch (Exception $e)
        {
            return 0;
        }
    }
}
?>