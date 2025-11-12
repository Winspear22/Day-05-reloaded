<?php

namespace App\Service;

use Exception;
use Throwable;
use RuntimeException;
use Doctrine\DBAL\Connection;

class ReadCommentsService
{
	public function __construct(
		private readonly Connection $connection
	) {}
	public function getAllComments(string $tableName): array
	{
		$sql = "SELECT * FROM $tableName";
		try
		{
			return $this->connection->fetchAllAssociative($sql);
		}
		catch (Exception $e)
		{
			throw new RuntimeException("Error, we cannot display the comments list, there was a problem in the table $tableName : " . $e->getMessage());
		}
	}
	public function getCommentById(string $tableName, int $id): ?array
	{
		try
		{
			$comment = $this->connection->fetchAssociative("SELECT * FROM $tableName WHERE id = :id", ['id' => $id]);
			return $comment ?: null;
		}
		catch (Throwable $e)
		{
			throw new RuntimeException('Error, we could not fetch the comment, it probably does not exist : ' . $e->getMessage());
		}
	}
}