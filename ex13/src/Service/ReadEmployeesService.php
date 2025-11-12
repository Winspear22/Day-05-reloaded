<?php

namespace App\Service;

use App\Entity\Employee;
use App\Repository\EmployeeRepository;
use Exception;
use App\Service\UtilsTableService;

class ReadEmployeesService 
{
    public function __construct(
        private readonly EmployeeRepository $repo,
        private readonly UtilsTableService $utilsTableService
    ) {}

    public function getAllEmployees(): array
    {
        try
		{
            if (!$this->utilsTableService->checkTableExistence('ex13_employees'))
                return [];

            $employees = $this->repo->findAll();
            return $employees ?? [];
        }
		catch (Exception $e)
		{
            return [];
        }
    }

    public function getEmployeeById(int $id): ?Employee
    {
        try
		{
            if (!$this->utilsTableService->checkTableExistence('ex13_employees'))
                return null;

            $employee = $this->repo->find($id);
            return $employee;
        }
		catch (Exception $e)
		{
			return null;
        }
	}
}