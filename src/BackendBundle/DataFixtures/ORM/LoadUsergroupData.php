<?php
/**
 * Created by PhpStorm.
 * User: francisco
 * Date: 11.10.16
 * Time: 11:19
 */

namespace BackendBundle\DataFixtures\ORM;


use BackendBundle\Entity\User;
use BackendBundle\Entity\Usergroup;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadUsergroupData implements FixtureInterface
{
    static public $users = array();
    static public $usergroups = array();

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


        $usergroup = new Usergroup();
        $usergroup->setName('name');
        $usergroup->setDateValidFrom(new \DateTime('2015-10-11 12:22:25'));
        $usergroup->setDateValidTo(null);
        $usergroup->setDateLastEdited(new \DateTime('2015-10-11 12:22:25'));


        $user2->setUsergroup($usergroup);
        $user2->setOwner(1);

        $manager->persist($user);
        $manager->persist($user2);
        $manager->persist($usergroup);


        $manager->flush();


        self::$users = array($user, $user2);
        self::$usergroups = array($usergroup);

    }
}