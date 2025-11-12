<?php

namespace App\Service;

use Throwable;
use Doctrine\DBAL\Connection;

class InsertCommentsService
{
	public function __construct(
		private readonly Connection $connection
	) {}
    public function insertCommentVulnerable(string $tableName, string $comment): string
    {
        $sql = "INSERT INTO $tableName (comment)
                VALUES ('$comment')";
        try
        {
            $this->connection->executeStatement($sql);
            return "success:Success! Non-secure comment inserted!";
        }
        catch (Throwable $e)
        {
            return "danger:Error, there was a problem with the database or the table $tableName : " . $e->getMessage();
        }
    }
    public function insertCommentSecure(string $tableName, string $comment): string
    {
        $sql = "INSERT INTO $tableName (comment)
                VALUES (:comment)";
        try
        {
            $this->connection->executeStatement($sql, ['comment' => $comment]);
            return "success:Success! Secure comment inserted!";
        }
        catch (Throwable $e)
        {
            return "danger:Error, there was a problem with the database or the table $tableName : " . $e->getMessage();
        }
    }
}