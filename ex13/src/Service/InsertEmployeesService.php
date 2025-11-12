<?php

namespace App\Service;

use Exception;
use App\Entity\Employee;
use App\Repository\EmployeeRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\EmployeesValidationService;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

class InsertEmployeesService
{
	public function __construct(
        private readonly EntityManagerInterface $em,
		private readonly EmployeeRepository $repo,
		private readonly UtilsTableService $utilsTableService,
		private readonly EmployeesValidationService $validation
	) {}

    public function insertEmployee(Employee $employee): string
    {
		try
		{
			if (!$this->utilsTableService->checkTableExistence('ex13_employees'))
                return 'danger:Table ex13_employees does not exist.';
            $validationErrors = $this->validation->validateAll($employee);

            if (!empty($validationErrors))
                return 'danger:' . implode(' | ', $validationErrors);
			$this->em->persist($employee);
			$this->em->flush();
			return "success: Employee created successfully!";
		}
		catch (UniqueConstraintViolationException $e)
		{
			return "danger: Email already exists!: " . $e->getMessage();
		}
		catch (Exception $e) 
		{
			return "danger: " . $e->getMessage();
		}
    }
}
?>