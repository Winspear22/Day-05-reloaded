<?php

namespace App\Service;

use Exception;
use Doctrine\DBAL\Connection;
use App\Service\DeleteDataServiceORM;
use App\Service\DeleteDataServiceSQL;

class DeleteAllDataService
{
	public function __construct(
		private readonly DeleteDataServiceSQL $deleteSql,
        private readonly DeleteDataServiceORM $deleteOrm

	) {}
	public function deleteAllData(string $tableNameSql, string $tableNameOrm): array
	{
		try
		{
			$resultSql = $this->deleteSql->deleteAllDataSQL($tableNameSql);
			$resultOrm = $this->deleteOrm->deleteAllDataORM($tableNameOrm);
			
			if (strpos($resultSql, 'danger') === 0)
			{
				return [
					'success' => false,
					'message' => $resultSql
				];
			}
			
			if (strpos($resultOrm, 'danger') === 0)
			{
				return [
					'success' => false,
					'message' => $resultOrm
				];
			}

			if (strpos($resultSql, 'info') === 0)
			{
				return [
					'success' => false,
					'message' => $resultSql
				];
			}
			
			if (strpos($resultOrm, 'info') === 0)
			{
				return [
					'success' => false,
					'message' => $resultOrm
				];
			}
			
			return [
				'success' => true,
				'message' => 'All data deleted successfully from both SQL and ORM!'
			];
		}
		catch (Exception $e)
		{
			return [
				'success' => false,
				'message' => 'Error deleting all data: ' . $e->getMessage()
			];
		}
	}


}