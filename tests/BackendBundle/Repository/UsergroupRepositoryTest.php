<?php

namespace tests\BackendBundle\Repository;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UsergroupRepositoryTest extends KernelTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        self::bootKernel();

        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testSearchByCategoryName()
    {
        $usergroups = $this->em
            ->getRepository('BackendBundle:Usergroup')
            ->findOneBy(array('id' => 1))
        ;

        $this->assertCount(1, $usergroups);
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown()
    {
        parent::tearDown();

        $this->em->close();
        $this->em = null; // avoid memory leaks
    }
}