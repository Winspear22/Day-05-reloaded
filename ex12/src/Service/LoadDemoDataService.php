<?php

namespace App\Service;

use Exception;
use Symfony\Component\Process\Process;

class LoadDemoDataService
{
    public function loadData(): string
    {
        try
        {
            $process = new Process([
                'php',
                'bin/console',
                'doctrine:fixtures:load',
                '--no-interaction'
            ]);
            
            $process->setWorkingDirectory(__DIR__ . '/../../');
            $process->run();
            
            if ($process->isSuccessful())
                return 'success: Data loaded successfully!';
            else
                return 'error: ' . $process->getErrorOutput();
        }
        catch (Exception $e)
        {
            return 'error: ' . $e->getMessage();
        }
    }
}