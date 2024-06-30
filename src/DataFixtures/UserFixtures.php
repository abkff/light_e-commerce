<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker;


class UserFixtures extends Fixture
{
    public const USER_REFERENCE = 'user';
    private $encode;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->encode = $userPasswordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');
        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setLastname($faker->lastName);
            $user->setFirstname($faker->firstName);
            $user->setEmail($faker->email);
            $user->setTown($faker->city);
            $user->setCodeP($faker->postcode);
            $user->setAdress($faker->streetAddress);
            $password = $this->encode->hashPassword($user, '123456');
            $user->setPassword($password);
            $manager->persist($user);
            $manager->flush();

        }
        $this->addReference(self::USER_REFERENCE, $user);
    }


}
