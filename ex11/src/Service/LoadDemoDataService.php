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

            // ✅ ÉTAPE 1 : Désactiver les contraintes FK temporairement
            $this->connection->executeStatement("SET FOREIGN_KEY_CHECKS=0");

            // ✅ ÉTAPE 2 : Vider les tables
            $this->connection->executeStatement("TRUNCATE TABLE ex11_bank_accounts");
            $this->connection->executeStatement("TRUNCATE TABLE ex11_addresses");
            $this->connection->executeStatement("TRUNCATE TABLE ex11_persons");
            // TRUNCATE réinitialise aussi l'auto_increment à 1

            // ✅ ÉTAPE 3 : Réactiver les contraintes FK
            $this->connection->executeStatement("SET FOREIGN_KEY_CHECKS=1");

            // Insérer 20 personnes (IDs seront 1-20)
            for ($i = 1; $i <= 20; $i++)
            {
                $sql = "INSERT INTO ex11_persons (username, name, email, enable, birthdate) 
                        VALUES (?, ?, ?, ?, ?)";
                $this->connection->executeStatement($sql, [
                    $faker->unique()->userName(),
                    $faker->name(),
                    $faker->unique()->email(),
                    $faker->numberBetween(0, 1),
                    $faker->dateTime()->format('Y-m-d H:i:s')
                ]);
            }

            // Insérer adresses avec IDs 1-20
            for ($i = 1; $i <= 20; $i++)
            {
                $sql = "INSERT INTO ex11_addresses (person_id, address) 
                        VALUES (?, ?)";
                $this->connection->executeStatement($sql, [
                    $i,
                    $faker->address()
                ]);
            }

            // Insérer comptes bancaires avec IDs 1-20
            for ($i = 1; $i <= 20; $i++)
            {
                $sql = "INSERT INTO ex11_bank_accounts (person_id, iban, bank_name) 
                        VALUES (?, ?, ?)";
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
            // ✅ En cas d'erreur, réactiver les FK
            try {
                $this->connection->executeStatement("SET FOREIGN_KEY_CHECKS=1");
            } catch (Exception $ignored) {}
            
            return "danger:Erreur lors du chargement des données : " . $e->getMessage();
        }
    }
}
