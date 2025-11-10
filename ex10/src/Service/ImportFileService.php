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
	

    public function importFile(string $filePath, string $tableName): array
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
            $this->createTableServiceSQL->createTableSQL($tableName);
            $this->createTableServiceORM->createTableORM($tableName);
            
            foreach ($content as $line)
                $this->insertDataServiceSQL->insertDataSQL($tableName, $line, $date);
            
            foreach ($content as $line)
            {
                $dataEntity = new Data();
                $dataEntity->setData($line);
                $dataEntity->setDate($date);
                $this->InsertDataServiceORM->insertDataORM($tableName, $dataEntity);
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