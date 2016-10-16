<?php

namespace Tests\BackendBundle\Services;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UsergroupServicesTest extends KernelTestCase
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

        $this->helper = static::$kernel->getContainer()
            ->get('app.helpers')
        ;
    }

    public function testSearchByCategoryName()
    {
        $products = $this->helper->json();
        $this->assertCount(1, $products);
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown()
    {
        parent::tearDown();

//        $this->em->close();
//        $this->em = null; // avoid memory leaks
    }
}