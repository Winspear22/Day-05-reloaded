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

        // Vider dans l'ordre (enfants d'abord)
        $this->connection->executeStatement("DELETE FROM ex11_bank_accounts");
        $this->connection->executeStatement("DELETE FROM ex11_addresses");
        $this->connection->executeStatement("DELETE FROM ex11_persons");

        // Insérer 20 personnes
        for ($i = 0; $i < 20; $i++)
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

        // ✅ Récupère les vrais IDs des personnes
        $personIds = $this->connection->fetchAllAssociative(
            "SELECT id FROM ex11_persons ORDER BY id"
        );

        // Insérer les adresses avec les VRAIS IDs
        foreach ($personIds as $person)
        {
            $sql = "INSERT INTO ex11_addresses (person_id, address) 
                    VALUES (?, ?)";
            $this->connection->executeStatement($sql, [
                $person['id'],  // ✅ Utilise le vrai ID
                $faker->address()
            ]);
        }

        // Insérer les comptes bancaires avec les VRAIS IDs
        foreach ($personIds as $person)
        {
            $sql = "INSERT INTO ex11_bank_accounts (person_id, iban, bank_name) 
                    VALUES (?, ?, ?)";
            $this->connection->executeStatement($sql, [
                $person['id'],  // ✅ Utilise le vrai ID
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
