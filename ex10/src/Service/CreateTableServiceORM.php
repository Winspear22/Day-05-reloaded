<?php 

namespace App\Service;

use Exception;
use Doctrine\DBAL\Connection;
use Symfony\Component\Process\Process;

class CreateTableServiceORM
{
	public function __construct(
		private readonly UtilsService $utilsService
	) {}

	public function createTableORM(string $tableName): string
	{
		try
		{
			if ($this->utilsService->checkTableExistenceORM($tableName))
				return 'info: Table already exists.';
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