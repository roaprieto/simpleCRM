<?php

namespace BackendBundle\DataFixtures\ORM;


use BackendBundle\Entity\User;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadUserData implements FixtureInterface
{
    static public $users = array();

    /*
    public function load(ObjectManager $manager)
    {
        $loader = new Loader();
        $objects = $loader->load(__DIR__.'/../users.yml');

        $persister = new Doctrine($manager);
        $persister->persist($objects);
    }
    */


    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('CurrentUser');
        $user->setEmail('user1@hotmail.com');
        $user->setPassword(hash('sha512', 'user1'));
        $user->setDateValidFrom(new \DateTime('2016-10-11 12:22:25'));
        $user->setProfilePicture('test.jpg');
        $user->setDateValidTo(null);
        $user->setDateLastEdited(new \DateTime('2016-10-11 12:22:25'));
        $user->setUsergroup(null);

        $user2 = new User();
        $user2->setUsername('AnotherUser');
        $user2->setEmail('user2@hotmail.com');
        $user2->setPassword(hash('sha512', 'user1'));
        $user2->setDateValidFrom(new \DateTime('2016-10-11 12:22:25'));
        $user2->setProfilePicture('test.jpg');
        $user2->setDateValidTo(null);
        $user2->setDateLastEdited(new \DateTime('2016-10-11 12:22:25'));
        $user2->setUsergroup(null);

        $manager->persist($user);
        $manager->persist($user2);

        $manager->flush();


        self::$users = array($user, $user2);

    }
}