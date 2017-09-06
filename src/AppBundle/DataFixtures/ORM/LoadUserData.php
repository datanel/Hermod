<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;

class LoadUserData implements FixtureInterface
{
    private $users = [
        [
            'username' => 'test',
            'token' => 'a2540dc6-5b0b-45b9-8a7d-8c6fcf03e1df'
        ]
    ];

    private function createUser(array $data)
    {
        return new User($data['username'], $data['token']);
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->users as $user) {
            $manager->persist($this->createUser($user));
        }
        $manager->flush();
    }
}