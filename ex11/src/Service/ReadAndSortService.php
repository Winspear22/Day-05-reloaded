<?php

namespace App\Service;

use Exception;
use Doctrine\DBAL\Connection;

class ReadAndSortService
{

	public function __construct(
		private readonly Connection $sql_connection
	) {}
	public function getAllPersons(string $tableName): array
	{
		$sql = "SELECT * FROM $tableName";
		try
		{
			return $this->sql_connection->fetchAllAssociative($sql);
		}
		catch (Exception $e)
		{
			return [];
		}
	}

	public function SortAndGroup()
	{}



}