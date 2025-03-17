<?php

namespace App\DataFixtures;

use App\Entity\Livres;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        for ($i = 0; $i < 100; $i++) {
        $livre = new Livres();
        $titre = $faker->name();
        $livre->setTitre($titre)->setSlug(strtolower(str_replace('','-',$titre)))->setIsbn($faker->isbn13())->setResume($faker->text)->setEditeur($faker->company())->setDateedition($faker->dateTimeBetween('-5 year','now'))->setImage("https://picsum.photos/seed/picsum/200/?id=".$i)->setPrix(rand(10,1000));

        $manager->persist($livre);


    }
        $manager->flush();
    }
}
