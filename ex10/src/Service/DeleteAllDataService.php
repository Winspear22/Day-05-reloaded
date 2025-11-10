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
    public function deleteAllData(string $tableNameSql, string $tableNameOrm): string
    {
        try
        {
            $resultOrm = $this->deleteOrm->deleteAllDataORM($tableNameOrm);
            $resultSql = $this->deleteSql->deleteAllDataSQL($tableNameSql);
            
            if (strpos($resultOrm, 'danger') === 0 || strpos($resultOrm, 'info') === 0)
                return $resultOrm;
            
            if (strpos($resultSql, 'danger') === 0 || strpos($resultSql, 'info') === 0)
                return $resultSql;
            
            return 'success:All data deleted successfully from both SQL and ORM!';
        }
        catch (Exception $e)
        {
            return 'danger:Error deleting all data: ' . $e->getMessage();
        }
    }
}