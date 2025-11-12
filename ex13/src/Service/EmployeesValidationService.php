<?php

namespace App\Service;

use App\Entity\Employee;
use App\Enum\PositionEnum;
use App\Repository\EmployeeRepository;

class EmployeesValidationService
{
	public function __construct(
        private readonly EmployeeRepository $employeeRepository,
		private readonly EmployeesValidationUtilsService $validation_utils
    ) {}

	public function validateAll(Employee $employee): array
	{
		$errors = [];
        $errors = array_merge($errors, $this->validateCEO($employee));
        $errors = array_merge($errors, $this->validateCOO($employee));
        $errors = array_merge($errors, $this->validateManagers($employee));
        $errors = array_merge($errors, $this->validateStaff($employee));
        $errors = array_merge($errors, $this->validatePromotions($employee));
        return $errors;
	}
	
	public function validateCEO(Employee $employee): array
    {
		$errors = [];
		$employee_nb = $this->employeeRepository->count([]);
		$current_employee_position = $employee->getPosition();
		if ($employee->getId() === null && $employee_nb === 0)
		{
			if ($current_employee_position !== PositionEnum::CEO)
				$errors[] = "Error: The first employee must be the CEO !";
		}
		if ($current_employee_position === PositionEnum::CEO && $employee->getManager() !== null)
            $errors[] = 'Error: The CEO cannot have a manager.';
		if ($current_employee_position === PositionEnum::CEO)
		{
            $existingCEO = $this->employeeRepository->findOneBy(['position' => PositionEnum::CEO]);
            if ($existingCEO && $existingCEO->getId() !== $employee->getId())
                $errors[] = 'Error: There can only be one CEO in the company.';
        }
		return $errors;
	}

	public function validateCOO(Employee $employee): array
    {
		$errors = [];
		$employee_nb = $this->employeeRepository->count([]);
		$current_employee_position = $employee->getPosition();
		if ($employee_nb === 1 && $employee->getId() === null)
		{
			if ($current_employee_position != PositionEnum::COO)
				$errors[] = "Error: The second employee must be the COO !";
		}
		if ($current_employee_position === PositionEnum::COO)
		{
            $existingCOO = $this->employeeRepository->findOneBy(['position' => PositionEnum::COO]);
            if ($existingCOO && $existingCOO->getId() !== $employee->getId())
                $errors[] = 'Error: There can only be one COO in the company.';
        }
		if ($current_employee_position === PositionEnum::COO)
		{
            if ($employee->getManager() === null || $employee->getManager()->getPosition() !== PositionEnum::CEO)
                $errors[] = 'Error: The COO must have the CEO as manager.';
        }
		if ($employee->getId() !== null)
		{
            $currentCOO = $this->employeeRepository->find($employee->getId());
            if ($currentCOO && $currentCOO->getPosition() === PositionEnum::COO)
			{
                if ($employee->getPosition() !== PositionEnum::COO)
                    $errors[] = 'Error: The COO position cannot be changed.';
            }
        }
		return $errors;
	}

	public function validateManagers(Employee $employee): array
    {
        $errors = [];

        $managerPositions = [
            PositionEnum::MANAGER,
            PositionEnum::ACCOUNT_MANAGER,
            PositionEnum::QA_MANAGER,
            PositionEnum::DEV_MANAGER
        ];

        if (!in_array($employee->getPosition(), $managerPositions))
            return $errors;

        if ($employee->getManager() === null || $employee->getManager()->getPosition() !== PositionEnum::COO)
            $errors[] = 'Error: Managers can only be managed by the COO.';

        // Manager ne peut pas manager un autre manager
        foreach ($employee->getEmployees() as $managed)
		{
            if (in_array($managed->getPosition(), $managerPositions))
			{
                $errors[] = 'Error: A manager cannot manage another manager.';
                break;
            }
        }

        // Vérifier qui peut manager qui
        foreach ($employee->getEmployees() as $managed)
		{
            if (!$this->validation_utils->canManagePosition($employee->getPosition(), $managed->getPosition()))
                $errors[] = $employee->getPosition()->value . ' cannot manage ' . $managed->getPosition()->value . '.';
        }

        return $errors;
	}

	public function validateStaff(Employee $employee): array
    {
        $errors = [];

        // Dev (Backend/Frontend) => Dev Manager ou Manager
        if (in_array($employee->getPosition(), [PositionEnum::BACKEND_DEV, PositionEnum::FRONTEND_DEV]))
		{
            $managerPosition = $employee->getManager()?->getPosition();
            if ($managerPosition !== PositionEnum::DEV_MANAGER && $managerPosition !== PositionEnum::MANAGER)
                $errors[] = 'Dev can only be managed by Dev Manager or Manager.';
        }

        // QA Tester => QA Manager ou Manager
        if ($employee->getPosition() === PositionEnum::QA_TESTER)
		{
            $managerPosition = $employee->getManager()?->getPosition();
            if ($managerPosition !== PositionEnum::QA_MANAGER && $managerPosition !== PositionEnum::MANAGER)
                $errors[] = 'QA Tester can only be managed by QA Manager or Manager.';
        }
        // Aucun staff ne peut être managé par Account Manager
        if ($employee->getManager() && $employee->getManager()->getPosition() === PositionEnum::ACCOUNT_MANAGER)
            $errors[] = 'Staff cannot be managed by Account Manager.';
        return $errors;
	}

	public function validatePromotions(Employee $employee): array
    {
		$errors = [];

        if ($employee->getId() === null)
            return $errors; // Pas de promotion si c'est une création

        $original = $this->employeeRepository->find($employee->getId());
        if ($original === null || $original->getPosition() === $employee->getPosition())
            return $errors; // Pas de changement de poste

        // Hiérarchie des postes
        $hierarchy = [
            PositionEnum::CEO => 5,
            PositionEnum::COO => 4,
            PositionEnum::MANAGER => 3,
            PositionEnum::DEV_MANAGER => 3,
            PositionEnum::QA_MANAGER => 3,
            PositionEnum::ACCOUNT_MANAGER => 3,
            PositionEnum::BACKEND_DEV => 2,
            PositionEnum::FRONTEND_DEV => 2,
            PositionEnum::QA_TESTER => 2
        ];

        $currentLevel = $hierarchy[$original->getPosition()->value] ?? 0;
        $newLevel = $hierarchy[$employee->getPosition()->value] ?? 0;

        // Pas de rétrogradation
        if ($newLevel < $currentLevel)
		{
            $errors[] = 'Demotion is not allowed.';
            return $errors;
        }

        // Dev ne peut devenir que Dev Manager
        if (in_array($original->getPosition(), [PositionEnum::BACKEND_DEV, PositionEnum::FRONTEND_DEV])) {
            if ($employee->getPosition() !== PositionEnum::DEV_MANAGER)
                $errors[] = 'Developer can only be promoted to Dev Manager.';
			else
			{
                // Auto-assign COO as manager
                $coo = $this->employeeRepository->findOneBy(['position' => PositionEnum::COO]);
                if ($coo)
                    $employee->setManager($coo);
            }
        }
        // QA Tester ne peut devenir que QA Manager
		if ($original->getPosition() === PositionEnum::QA_TESTER)
		{
			if ($employee->getPosition() !== PositionEnum::QA_MANAGER)
				$errors[] = 'QA Tester can only be promoted to QA Manager.';
			else
			{
				// Auto-assign COO as manager
				$coo = $this->employeeRepository->findOneBy(['position' => PositionEnum::COO]);
				if ($coo)
					$employee->setManager($coo);
			}
		}
		return $errors;
	}
}
?>