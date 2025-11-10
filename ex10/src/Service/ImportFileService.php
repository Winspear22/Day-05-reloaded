<?php

namespace App\Service;

use DateTime;
use Exception;
use App\Entity\Data;
use App\Service\UtilsService;
use Doctrine\DBAL\Connection;

class ImportFileService
{
	public function __construct(
		private readonly Connection $sql_connection,
		private readonly UtilsService $utils_service,
		private readonly InsertDataServiceORM $InsertDataServiceORM,
		private readonly InsertDataServiceSQL $insertDataServiceSQL,
		private readonly CreateTableServiceSQL $createTableServiceSQL,
		private readonly CreateTableServiceORM $createTableServiceORM,
	) {}
	

    public function importFile(string $filePath, string $tableNameSql, string $tableNameOrm): array
    {
        try
        {
            if (!file_exists($filePath))
                return ['success' => false, 'message' => 'Error ! Could not find file: ' . basename($filePath)];
            
            $content = array_map(
                fn ($line) => htmlspecialchars($line, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'),
                file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES)
            );
            
            if (empty($content))
                return ['success' => false, 'message' => 'Error ! The file is empty.'];
            
            $date = new DateTime();
            $this->createTableServiceSQL->createTableSQL($tableNameSql);
            $this->createTableServiceORM->createTableORM($tableNameOrm);
            foreach ($content as $line)
            {
                $result = $this->insertDataServiceSQL->insertDataSQL($tableNameSql, $line, $date);
                if (strpos($result, 'danger') === 0)
                    return ['success' => false, 'message' => $result];
            }

            foreach ($content as $line)
            {
                $dataEntity = new Data();
                $dataEntity->setData($line);
                $dataEntity->setDate($date);
                $result = $this->InsertDataServiceORM->insertDataORM($tableNameOrm, $dataEntity);
                if (strpos($result, 'danger') === 0)
                    return ['success' => false, 'message' => $result];
            }
            
            return ['success' => true, 'message' => 'Success! The SQL and ORM import was successful!'];
        }
        catch (Exception $e)
        {
            return ['success' => false, 'message' => 'Error during the file import: ' . $e->getMessage()];
        }
    }
}
?>