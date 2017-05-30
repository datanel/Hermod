<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;

class LoadUserData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $user = new User('test', 'a2540dc6-5b0b-45b9-8a7d-8c6fcf03e1df');

        $manager->persist($user);
        $manager->flush();
    }
}