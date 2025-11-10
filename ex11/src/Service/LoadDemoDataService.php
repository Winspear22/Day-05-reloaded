<?php

namespace App\Service;

use Faker\Factory;
use Doctrine\DBAL\Connection;
use Exception;

class LoadDemoDataService
{
    public function __construct(private readonly Connection $connection) {}

    public function loadData(): string
    {
        try
        {
            $faker = Factory::create('fr_FR');

            // Vider les tables (respecter les FK)
            $this->connection->executeStatement("DELETE FROM ex11_bank_accounts");
            $this->connection->executeStatement("DELETE FROM ex11_addresses");
            $this->connection->executeStatement("DELETE FROM ex11_persons");

            // Insérer 20 personnes
            for ($i = 1; $i <= 20; $i++)
            {
                $sql = "INSERT INTO ex11_persons (username, name, email, enable, birthdate) 
                        VALUES (?, ?, ?, ?, ?)";
                $this->connection->executeStatement($sql, [
                    $faker->unique()->userName(),
                    $faker->name(),
                    $faker->unique()->email(),
                    $faker->boolean(),
                    $faker->dateTime()->format('Y-m-d H:i:s')
                ]);
            }

            // Insérer 20 adresses
            for ($i = 1; $i <= 20; $i++)
            {
                $sql = "INSERT INTO ex11_addresses (person_id, address) 
                        VALUES (?, ?)";
                $this->connection->executeStatement($sql, [
                    $i,
                    $faker->address()
                ]);
            }

            // Insérer 20 comptes bancaires
            for ($i = 1; $i <= 20; $i++)
            {
                $sql = "INSERT INTO ex11_bank_accounts (person_id, iban, bank_name) 
                        VALUES (?, ?)";
                $this->connection->executeStatement($sql, [
                    $i,
                    $faker->iban('FR'),
                    $faker->company()
                ]);
            }

            return "success:20 personnes, adresses et comptes bancaires chargés !";
        }
        catch (Exception $e)
        {
            return "danger:Erreur lors du chargement des données : " . $e->getMessage();
        }
    }
}
