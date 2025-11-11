<?php 

namespace App\Service;

use Exception;
use App\Repository\PersonRepository;
use App\Service\UtilsTableService;
use Doctrine\ORM\EntityManagerInterface;

class DeleteTableService 
{
	public function __construct(
        private readonly EntityManagerInterface $em,
		private readonly PersonRepository $repo,
        private readonly UtilsTableService $utilsTableService
	) {}
    
    public function deleteAllTableContent(): string
    {
        try
        {
            if (!$this->utilsTableService->checkTableExistence('ex12_persons'))
                return 'danger:Table ex12_persons does not exist.';

            if (!$this->utilsTableService->checkTableExistence('ex12_addresses'))
                return 'danger:Table ex12_addresses does not exist.';

            if (!$this->utilsTableService->checkTableExistence('ex12_bank_accounts'))
                return 'danger:Table ex12_bank_accounts does not exist.';

            $persons = $this->repo->findAll();

            if (empty($persons))
                return 'info:No data to delete.';

            foreach ($persons as $person)
                $this->em->remove($person);
            $this->em->flush();
            return 'success:All data deleted successfully!';
        }
        catch (Exception $e)
        {
            return 'danger:' . $e->getMessage();
        }
    }
}