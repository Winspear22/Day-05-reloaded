<?php 
namespace App\Service;

use Exception;
use App\Entity\Employee;
use App\Repository\EmployeeRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\EmployeesValidationService;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

class UpdateEmployeesService
{
		public function __construct(
        private readonly EntityManagerInterface $em,
		private readonly EmployeeRepository $repo,
		private readonly UtilsTableService $utilsTableService,
		private readonly EmployeesValidationService $validation
	) {}

	public function updateEmployee(Employee $employee): string
	{
		try
		{
			if (!$this->utilsTableService->checkTableExistence('ex13_employees'))
				return 'danger:Table ex13_employees does not exist.';

			$validationErrors = $this->validation->validateAll($employee);

			if (!empty($validationErrors))
				return 'danger:' . implode(' | ', $validationErrors);

			$this->em->flush();
			return "success: Employee updated successfully!";
		}
		catch (UniqueConstraintViolationException $e)
		{
			return "danger: Email already exists!";
		}
		catch (Exception $e)
		{
			return "danger: " . $e->getMessage();
		}
	}

}

?>