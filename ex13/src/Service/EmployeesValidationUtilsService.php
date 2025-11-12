<?php

namespace App\Service;

use App\Entity\Employee;
use App\Enum\PositionEnum;
use App\Repository\EmployeeRepository;

class EmployeesValidationUtilsService
{
	public function __construct(
		private readonly EmployeeRepository $employeeRepository
	) {}
	public function canManagePosition(PositionEnum $manager, PositionEnum $managed): bool
    {
        if ($manager === PositionEnum::QA_MANAGER)
            return $managed === PositionEnum::QA_TESTER;
        if ($manager === PositionEnum::DEV_MANAGER)
            return $managed === PositionEnum::BACKEND_DEV || $managed === PositionEnum::FRONTEND_DEV;
        if ($manager === PositionEnum::MANAGER)
		{
            return $managed === PositionEnum::QA_TESTER 
                || $managed === PositionEnum::BACKEND_DEV 
                || $managed === PositionEnum::FRONTEND_DEV;
        }
        return false;
    }

	public function canDeleteCEO(Employee $employee): bool
    {
        if ($employee->getPosition() !== PositionEnum::CEO)
            return true;
        $totalEmployees = $this->employeeRepository->count([]);
		if ($totalEmployees <= 1)
			return true;
        return false;
    }
	
	public function canDeleteCOO(Employee $employee): bool
    {
        if ($employee->getPosition() !== PositionEnum::COO)
            return true;
        $numberOfManaged = count($employee->getEmployees());
		if ($numberOfManaged === 0)
			return true;
		else
			return false;
    }
	
	public function canDeleteManager(Employee $employee): bool
    {
        $managerPositions = [
            PositionEnum::MANAGER,
            PositionEnum::ACCOUNT_MANAGER,
            PositionEnum::QA_MANAGER,
            PositionEnum::DEV_MANAGER
        ];

		if (!in_array($employee->getPosition(), $managerPositions))
			return true;
		$numberOfManaged = count($employee->getEmployees());
		if ($numberOfManaged === 0)
			return true;
		else
			return false;
	}
}

?>