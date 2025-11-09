<?php 

namespace App\Service;

use Exception;
use Doctrine\DBAL\Connection;
use App\Service\UtilsTableService;
use Symfony\Component\Process\Process;

class DeleteTableService 
{
	public function __construct(
		private readonly Connection $orm_connection,
		private readonly UtilsTableService $utilsTableService
	) {}
	public function dropAllTables(string $tableName): string
    {
        try
        {
			if (!$this->utilsTableService->checkTableExistence($tableName))
				return 'info: Tables do not exist.';
            $process = new Process([
                'php',
                'bin/console',
                'doctrine:migrations:migrate',
                '0',
                '--no-interaction'
            ]);
            
            $process->setWorkingDirectory(__DIR__ . '/../../');
            $process->run();
            
            if ($process->isSuccessful())
                return 'success: All tables dropped successfully!';
            else
                return 'danger: ' . $process->getErrorOutput();
        }
        catch (Exception $e)
        {
            return 'danger: ' . $e->getMessage();
        }
    }
}