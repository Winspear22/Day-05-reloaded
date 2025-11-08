<?php

namespace App\Service;

use Exception;
use Doctrine\DBAL\Connection;
use App\Service\UtilsTableService;

class CreateBankAccountsTableService
{
	public function __construct(
		private readonly Connection $sql_connection,
		private readonly UtilsTableService $utilsTableService) {}
}

?>