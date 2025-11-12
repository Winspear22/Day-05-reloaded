<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\Employee;
use App\Enum\HoursEnum;
use App\Enum\PositionEnum;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // 1. CEO
        $ceo = new Employee();
        $ceo->setFirstname('John');
        $ceo->setLastname('CEO');
        $ceo->setEmail('ceo@company.com');
        $ceo->setBirthdate(new DateTime('1980-01-15'));
        $ceo->setEmployedSince(new DateTime('2010-01-01'));
        $ceo->setEmployedUntil(new DateTime('2014-05-01'));
        $ceo->setHours(HoursEnum::EIGHT);
        $ceo->setSalary(150000);
        $ceo->setPosition(PositionEnum::CEO);
        $ceo->setActive(true);
        $manager->persist($ceo);

        // 2. COO
        $coo = new Employee();
        $coo->setFirstname('Marie');
        $coo->setLastname('COO');
        $coo->setEmail('coo@company.com');
        $coo->setBirthdate(new DateTime('1982-03-20'));
        $coo->setEmployedSince(new DateTime('2011-06-01'));
        $coo->setEmployedUntil(new DateTime('2014-05-01'));
        $coo->setHours(HoursEnum::EIGHT);
        $coo->setSalary(120000);
        $coo->setPosition(PositionEnum::COO);
        $coo->setManager($ceo);
        $coo->setActive(true);
        $manager->persist($coo);

        // 3. Dev Manager
        $devManager = new Employee();
        $devManager->setFirstname('Thomas');
        $devManager->setLastname('Dev Manager');
        $devManager->setEmail('dev.manager@company.com');
        $devManager->setBirthdate(new DateTime('1985-05-10'));
        $devManager->setEmployedSince(new DateTime('2015-01-01'));
        $devManager->setEmployedUntil(new DateTime('2014-05-01'));
        $devManager->setHours(HoursEnum::EIGHT);
        $devManager->setSalary(90000);
        $devManager->setPosition(PositionEnum::DEV_MANAGER);
        $devManager->setManager($coo);
        $devManager->setActive(true);
        $manager->persist($devManager);

        // 4. QA Manager
        $qaManager = new Employee();
        $qaManager->setFirstname('Sophie');
        $qaManager->setLastname('QA Manager');
        $qaManager->setEmail('qa.manager@company.com');
        $qaManager->setBirthdate(new DateTime('1987-07-15'));
        $qaManager->setEmployedSince(new DateTime('2016-03-01'));
        $qaManager->setEmployedUntil(new DateTime('2014-05-01'));
        $qaManager->setHours(HoursEnum::EIGHT);
        $qaManager->setSalary(85000);
        $qaManager->setPosition(PositionEnum::QA_MANAGER);
        $qaManager->setManager($coo);
        $qaManager->setActive(true);
        $manager->persist($qaManager);

        // 5. Backend Dev
        $backendDev = new Employee();
        $backendDev->setFirstname('Alex');
        $backendDev->setLastname('Backend Dev');
        $backendDev->setEmail('alex.dev@company.com');
        $backendDev->setBirthdate(new DateTime('1990-02-20'));
        $backendDev->setEmployedSince(new DateTime('2018-01-15'));
        $backendDev->setEmployedUntil(new DateTime('2014-05-01'));
        $backendDev->setHours(HoursEnum::EIGHT);
        $backendDev->setSalary(65000);
        $backendDev->setPosition(PositionEnum::BACKEND_DEV);
        $backendDev->setManager($devManager);
        $backendDev->setActive(true);
        $manager->persist($backendDev);

        // 6. Frontend Dev
        $frontendDev = new Employee();
        $frontendDev->setFirstname('Lisa');
        $frontendDev->setLastname('Frontend Dev');
        $frontendDev->setEmail('lisa.dev@company.com');
        $frontendDev->setBirthdate(new DateTime('1991-08-10'));
        $frontendDev->setEmployedSince(new DateTime('2019-06-01'));
        $frontendDev->setEmployedUntil(new DateTime('2014-05-01'));
        $frontendDev->setHours(HoursEnum::EIGHT);
        $frontendDev->setSalary(62000);
        $frontendDev->setPosition(PositionEnum::FRONTEND_DEV);
        $frontendDev->setManager($devManager);
        $frontendDev->setActive(true);
        $manager->persist($frontendDev);

        // 7. QA Tester
        $qaTester = new Employee();
        $qaTester->setFirstname('Bob');
        $qaTester->setLastname('QA Tester');
        $qaTester->setEmail('bob.qa@company.com');
        $qaTester->setBirthdate(new DateTime('1993-11-05'));
        $qaTester->setEmployedSince(new DateTime('2020-09-01'));
        $qaTester->setEmployedUntil(new DateTime('2014-05-01'));
        $qaTester->setHours(HoursEnum::EIGHT);
        $qaTester->setSalary(50000);
        $qaTester->setPosition(PositionEnum::QA_TESTER);
        $qaTester->setManager($qaManager);
        $qaTester->setActive(true);
        $manager->persist($qaTester);

        // 8. Account Manager
        $accountManager = new Employee();
        $accountManager->setFirstname('Emma');
        $accountManager->setLastname('Account Manager');
        $accountManager->setEmail('emma.account@company.com');
        $accountManager->setBirthdate(new DateTime('1988-04-12'));
        $accountManager->setEmployedSince(new DateTime('2017-02-01'));
        $accountManager->setEmployedUntil(new DateTime('2014-05-01'));
        $accountManager->setHours(HoursEnum::EIGHT);
        $accountManager->setSalary(75000);
        $accountManager->setPosition(PositionEnum::ACCOUNT_MANAGER);
        $accountManager->setManager($coo);
        $accountManager->setActive(true);
        $manager->persist($accountManager);

        // 9. Manager (généraliste)
        $managerGeneral = new Employee();
        $managerGeneral->setFirstname('David');
        $managerGeneral->setLastname('Manager');
        $managerGeneral->setEmail('david.manager@company.com');
        $managerGeneral->setBirthdate(new DateTime('1984-09-25'));
        $managerGeneral->setEmployedSince(new DateTime('2014-05-01'));
        $managerGeneral->setEmployedUntil(new DateTime('2014-05-01'));
        $managerGeneral->setHours(HoursEnum::EIGHT);
        $managerGeneral->setSalary(95000);
        $managerGeneral->setPosition(PositionEnum::MANAGER);
        $managerGeneral->setManager($coo);
        $managerGeneral->setActive(true);
        $manager->persist($managerGeneral);

        // 10. Dev à temps partiel
        $partTimeDev = new Employee();
        $partTimeDev->setFirstname('Sarah');
        $partTimeDev->setLastname('Part-Time Dev');
        $partTimeDev->setEmail('sarah.dev@company.com');
        $partTimeDev->setBirthdate(new DateTime('1995-06-30'));
        $partTimeDev->setEmployedSince(new DateTime('2021-01-01'));
        $partTimeDev->setEmployedUntil(new DateTime('2022-01-01'));
        $partTimeDev->setHours(HoursEnum::FOUR);
        $partTimeDev->setSalary(30000);
        $partTimeDev->setPosition(PositionEnum::BACKEND_DEV);
        $partTimeDev->setManager($devManager);
        $partTimeDev->setActive(true);
        $manager->persist($partTimeDev);

        $manager->flush();
    }
}
