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

    // Version 1 : SANS marital_status
    public function migrateToVersionWithoutMaritalStatus(): string
    {
        // Si déjà à V2, faire rollback de V2
        if ($this->maritalStatusExists())
            return $this->rollbackFromVersion('DoctrineMigrations\\Version20251109121119');
        
        if ($this->utilsTableService->checkTableExistence('ex09_persons'))
            return 'info: Already at Version 1 (WITHOUT marital_status).';
        
        return $this->executeCommand('DoctrineMigrations\\Version20251109120844');
    }

    // Version 2 : AVEC marital_status
    public function migrateToVersionWithMaritalStatus(): string
    {
        if ($this->maritalStatusExists())
            return 'info: Already at Version 2 (WITH marital_status).';
        
        return $this->executeCommand('DoctrineMigrations\\Version20251109121119');
    }

    private function executeCommand(string $version): string
    {
        try
        {
            $process = new Process([
                'php',
                'bin/console',
                'doctrine:migrations:migrate',
                $version,
                '--no-interaction'
            ]);
            
            $process->setWorkingDirectory(__DIR__ . '/../../');
            $process->run();
            
            if ($process->isSuccessful())
                return 'success: Migration successful!';
            else
                return 'error: ' . $process->getErrorOutput();
        }
        catch (Exception $e)
        {
            return 'error: ' . $e->getMessage();
        }
    }

    // ← NEW FUNCTION
    private function rollbackFromVersion(string $version): string
    {
        try
        {
            // Execute le down() de cette version
            $process = new Process([
                'php',
                'bin/console',
                'doctrine:migrations:execute',
                $version,
                '--down',  // ← Important !
                '--no-interaction'
            ]);
            
            $process->setWorkingDirectory(__DIR__ . '/../../');
            $process->run();
            
            if ($process->isSuccessful())
                return 'success: Rolled back to Version 1!';
            else
                return 'error: ' . $process->getErrorOutput();
        }
        catch (Exception $e)
        {
            return 'error: ' . $e->getMessage();
        }
    }

    private function maritalStatusExists(): bool
    {
        try
        {
            $schemaManager = $this->orm_connection->createSchemaManager();
            
            if (!$schemaManager->tablesExist(['ex09_persons']))
                return false;
            
            $table = $schemaManager->introspectTable('ex09_persons');
            return $table->hasColumn('marital_status');
        }
        catch (Exception $e)
        {
            return false;
        }
    }
}
?>