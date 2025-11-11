<?php 

namespace App\Service;

use Exception;
use Doctrine\DBAL\Connection;
use App\Service\UtilsTableService;
use Symfony\Component\Process\Process;

class CreateTableService 
{
    public function __construct(
        private readonly Connection $orm_connection,
        private readonly UtilsTableService $utilsTableService
    ) {}

	public function createTable(string $tableName): string
	{
		try
		{
			if ($this->utilsTableService->checkTableExistence($tableName)) {
				return 'info: Table already exists.';
			}

			$process = new Process([
				'php',
				'bin/console',
				'doctrine:migrations:migrate',
				'--no-interaction'
			]);
			$process->setWorkingDirectory(__DIR__ . '/../../');
			$process->run();
			if ($process->isSuccessful())
				return 'success: Table created successfully!';
			else
				return 'error: ' . $process->getErrorOutput();
		} 
		catch (Exception $e)
		{
			return 'error: ' . $e->getMessage();
		}
	}
}
?>