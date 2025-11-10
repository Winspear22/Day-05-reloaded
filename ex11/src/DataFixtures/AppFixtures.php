<?php

namespace App\DataFixtures;

use Faker\Factory;
use Doctrine\DBAL\Connection;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function __construct(
        private readonly Connection $connection) {}
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

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
    }
}
