<?php

namespace Tests\AppBundle\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    /** @var EntityManager $em */
    private $em;
    /** @var Client $client */
    private $client;

    public function setUp()
    {
        $fixtures = array('AppBundle\DataFixtures\ORM\LoadUserData');
        $this->loadFixtures($fixtures);
        $this->em = $this->getContainer()->get('doctrine')->getManager();
        $this->client = static::createClient();
    }

    protected function toTestFailedCase(
        $method, $code, $paramTestName, $param, $testName, $urlName, $jwtAuth = false, $file = false
    ) {
        $headers = array(
            'CONTENT_TYPE'          => 'application/json',
            'HTTP_X-Requested-With' => 'XMLHttpRequest'
        );

        if ($jwtAuth !== false) {
            if ($jwtAuth === true) {
                $headers['HTTP_Authorization'] = $this->jwt;
            } else {
                $headers['HTTP_Authorization'] = $jwtAuth;
            }
        }
        $params = array();
        if ($param !== false) {
            $params = $param;
        }
        $filesParams = array();
        if ($file !== false) {
            $filesParams = $file;
        }

        $this->client->request(
            $method,
            $this->getUrl($urlName),
            $params,
            $filesParams,
            $headers
        );

        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);
        $arrResponse = json_decode($response->getContent(), true);
        $this->assertEquals($arrResponse['code'], $code, $testName.' '.$paramTestName.' -> Recieved message:'.print_r($arrResponse['msg'], true));

    }

    protected function assertJsonResponse($response, $statusCode = 200)
    {
        $this->assertEquals(
            $statusCode, $response->getStatusCode(),
            $response->getContent()
        );
        $this->assertTrue(
            $response->headers->contains('Content-Type', 'application/json'),
            $response->headers
        );
    }

    public function testLoginAction()
    {
        /************************************************************************************************/
        /*************************************** TEST WRONG DATA ****************************************/
        /************************************************************************************************/
        $jsonFailedParams = array(
            'password_null' => '{"email":"test_new_user@email.com"}',
            'wrong_email_format' => '{"email":"test_new_usmail.com", "password":"franroa"}',
            'email_null' => '{"password":"franroa"}'
        );
        foreach ($jsonFailedParams as $paramTestName => $jsonValue) {
            $this->toTestFailedCase('POST', 400, $paramTestName, array('json' => $jsonValue), 'Login Test', 'login');
        }


        /************************************************************************************************/
        /*********************************** TEST USER DOESN'T EXIST ************************************/
        /************************************************************************************************/
        $json = '{"email":"test_new_user@email.com", "password":"franroa"}';
        $this->toTestFailedCase('POST', 404, 'user_doesnt_exist', array('json' => $json), 'Login Test', 'login');


        /************************************************************************************************/
        /************************************************************************************************/
        /********************************************** OK **********************************************/
        /************************************************************************************************/
        /************************************************************************************************/
        $this->client->request(
            'POST',
            $this->getUrl('login'),
            array('json' => '{"email":"user1@hotmail.com", "password":"user1", "gethash":"true"}'),
            array(),
            array(
                'CONTENT_TYPE'          => 'application/json',
                'HTTP_X-Requested-With' => 'XMLHttpRequest',
            )
        );

        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);
    }
}
