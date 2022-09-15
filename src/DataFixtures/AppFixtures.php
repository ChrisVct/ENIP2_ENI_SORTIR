<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\City;
use App\Entity\Event;
use App\Entity\Location;
use App\Entity\Status;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class AppFixtures extends Fixture
{
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        // Creation des campus
        $allCampus = [];
        $allCampusName = ['SAINT-HERBLAIN', 'CHARTRES DE BRETAGNE', 'LA ROCHE SUR YON'];
        foreach ($allCampusName as $name) {
            $campus = new Campus();
            $campus->setName($name);
            $allCampus[] = $campus;
            $manager->persist($campus);
        }
        // Creation des villes
        $citys = [];
        for ($i = 0; $i < 10; ++$i) {
            $city = new City();
            $city->setName($this->faker->city());
            $city->setZipCode($this->faker->postcode());
            $citys[] = $city;
            $manager->persist($city);
        }
        // Creation des Etats des sorties
        $status = [];
        $allState = ['Créer', 'Ouverte', 'Cloturée', 'Activité en cours', 'Passée', 'Annulée'];
        foreach ($allState as $wording) {
            $statu = new Status();
            $statu->setWording($wording);
            $status[] = $statu;
            $manager->persist($statu);
        }
        // Creation des lieux
        $locations = [];
        for ($i = 0; $i < 10; ++$i) {
            $location = new Location();
            $location->setName($this->faker->company())
            ->setLongitude($this->faker->longitude())
            ->setLatitude($this->faker->longitude())
            ->setCity($citys[mt_rand(0, count($citys) - 1)])
            ->setStreet($this->faker->streetName());
            $locations[] = $location;
            $manager->persist($location);
        }
        $admin = new User();
        $admin->setFirstName('admin')
            ->setLastName('admin')
            ->setEmail('admin@admin.fr')
            ->setCampus($allCampus[mt_rand(0, count($allCampus) - 1)])
            ->setIsAdmin(true)
            ->setIsActive(true)
            ->setPseudo('SuperTOTO')
            ->setPhoneNumber('0606060606');
        $admin->setPlainPassword('admin');
        $manager->persist($admin);
        $users = [];
        for ($i = 0; $i < 10; ++$i) {
            $user = new User();
            $user->setFirstName($this->faker->firstName())
                ->setLastName($this->faker->lastName())
                ->setEmail($this->faker->email())
                ->setCampus($allCampus[mt_rand(0, count($allCampus) - 1)])
                ->setPseudo($this->faker->userName());
            $user->setPlainPassword('password');
            $users[] = $user;
            $manager->persist($user);
        }
        $events = [];
        for ($i = 0; $i < 20; ++$i) {
            $event = new Event();
            $event->setName($this->faker->sentence(3))
                ->setStartAt(\DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween('+1 week', '+2 week', 'Europe/Paris')))
                ->setDeadLineInscriptionAt(\DateTimeImmutable::createFromMutable($this->faker->dateTime('+1 week', 'Europe/Paris')))
                ->setCampus($allCampus[mt_rand(0, count($allCampus) - 1)])
                ->setOrganizer($users[mt_rand(0, count($users) - 1)])
                ->setDuration(mt_rand(10, 180))
                ->setMaxPeople(mt_rand(1, 49))
                ->setLocation($locations[mt_rand(0, count($locations) - 1)])
                ->setDescription($this->faker->text(75))
                ->setStatus($status[mt_rand(0, count($status) - 1)]);
            $events[] = $event;
            $manager->persist($event);
        }
        foreach ($events as $event) {
            for ($i = 0; $i < count($users) - 1; ++$i) {
                $event->addRegistration($users[mt_rand(0, count($users) - 1)]);
                $manager->persist($event);
            }
        }

        $manager->flush();
    }
}
