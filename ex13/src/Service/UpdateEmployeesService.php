<?php 
namespace App\Service;

use Exception;
use App\Entity\Employee;
use App\Repository\EmployeeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

class UpdateEmployeesService
{
		public function __construct(
        private readonly EntityManagerInterface $em,
		private readonly EmployeeRepository $repo,
		private readonly UtilsTableService $utilsTableService
	) {}

    public function updateEmployee(Employee $employee): string
    {
        try
		{
			if (!$this->utilsTableService->checkTableExistence('ex13_employees'))
                return 'danger:Table ex13_employees does not exist.';
			$employee = $this->repo->find($employee->getId());
            if (!$employee)
				return 'danger:Error, employee not found.';
			$this->em->flush();
			return "success: Employee updated successfully!";
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