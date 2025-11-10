<?php

namespace App\Service;

use DateTime;
use Exception;
use App\Entity\Data;
use App\Service\UtilsService;
use Doctrine\DBAL\Connection;
use App\Service\ReadDataServiceSQL;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class ImportFileService
{
	public function __construct(
		private readonly Connection $sql_connection,
		private readonly UtilsService $utils_service,
		private readonly InsertDataServiceORM $InsertDataServiceORM,
		private readonly InsertDataServiceSQL $insertDataServiceSQL,
		private readonly CreateTableServiceSQL $createTableServiceSQL,
		private readonly CreateTableServiceORM $createTableServiceORM,
		private readonly FlashBagInterface $flashBag
	) {}
	
	public function importFile(string $filePath, string $tableName): void
    {
        try
		{
            if (!file_exists($filePath))
			{
                $this->flashBag->add('danger', 'Error ! Could not find file: ' . basename($filePath));
                return;
            }
            $content = array_map(
                fn ($line) => htmlspecialchars($line, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'),
                file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES)
            );
            $date = new DateTime();
            // Créer la table SQL si besoin
            $this->createTableServiceSQL->createTableSQL($tableName);
			$this->createTableServiceORM->createTableORM($tableName);
            // Insertion dans SQL
            foreach ($content as $line)
                $this->insertDataServiceSQL->insertDataSQL($tableName, $line, $date);
            // Insertion dans ORM
            foreach ($content as $line)
			{
                $dataEntity = new Data();
                $dataEntity->setData($line);
                $dataEntity->setDate($date);
                $this->InsertDataServiceORM->insertDataORM($tableName, $dataEntity);
            }
            $this->flashBag->add('success', 'Success! The SQL and ORM import was successful!');
        }
		catch (Exception $e)
		{
            $this->flashBag->add('danger', 'Error during the file import: ' . $e->getMessage());
        }
    }

}
?>