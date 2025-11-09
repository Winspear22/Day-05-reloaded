<?php 

namespace App\Service;

use Exception;
use Doctrine\DBAL\Connection;
use Symfony\Component\Process\Process;

class UtilsTableService 
{
	public function __construct(
		private readonly Connection $orm_connection
	) {}

	public function getMigrationStatus(): array
    {
        try
        {
            $process = new Process([
                'php',
                'bin/console',
                'doctrine:migrations:status',
                '--format=json'
            ]);
            
            $process->setWorkingDirectory(__DIR__ . '/../../');
            $process->run();
            
            if ($process->isSuccessful())
                return json_decode($process->getOutput(), true);
            else
                return ['error' => $process->getErrorOutput()];
        }
        catch (Exception $e)
        {
            return ['error' => $e->getMessage()];
        }
    }

	public function checkTableExistence(string $tableName): bool
	{
		try
		{
			$schemaManager = $this->orm_connection->createSchemaManager();
			return $schemaManager->tablesExist([$tableName]);
		}
		catch (Exception $e)
		{
			return false;
		}
	}
}