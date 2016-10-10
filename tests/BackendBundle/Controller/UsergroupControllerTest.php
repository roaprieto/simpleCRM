<?php

namespace BackendBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UsergroupControllerTest extends WebTestCase
{
    public function testNew()
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/usergroup/new',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"name":"Francisco"}'
        );


        $this->assertEquals(
            200, // or Symfony\Component\HttpFoundation\Response::HTTP_OK
            $client->getResponse()->getStatusCode()
        );
        $this->assertTrue(
            $client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            ),
            'the "Content-Type" header is "application/json"' // optional message shown on failure
        );
    }
}
