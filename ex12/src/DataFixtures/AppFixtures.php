<?php

namespace App\DataFixtures;

use App\Entity\Person;
use App\Entity\Address;
use App\Entity\BankAccount;
use Faker\Factory;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $persons = [];

        // Crée 20 personnes
        for ($i = 0; $i < 20; $i++)
        {
            $person = new Person();
            $person->setUsername($faker->unique()->userName());
            $person->setName($faker->name());
            $person->setEmail($faker->unique()->email());
            $person->setEnable($faker->boolean());
            $person->setBirthdate($faker->dateTime());

            $manager->persist($person);
            $persons[] = $person;  // ← Stocke la personne
        }

        // Flush pour que les IDs soient générés
        $manager->flush();

        // Crée 20 adresses ET comptes bancaires avec les vraies personnes
        foreach ($persons as $person)
        {
            // Adresse
            $address = new Address();
            $address->setAddress($faker->address());
            $address->setPerson($person);
            $manager->persist($address);

            // Compte bancaire
            $bankAccount = new BankAccount();
            $bankAccount->setIban($faker->iban('FR'));
            $bankAccount->setBankName($faker->company());
            $bankAccount->setPerson($person);
            $manager->persist($bankAccount);
        }

        // Flush final
        $manager->flush();
    }
}
