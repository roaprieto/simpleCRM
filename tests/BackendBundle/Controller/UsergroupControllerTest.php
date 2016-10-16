<?php

namespace BackendBundle\Tests\Controller;

use AppBundle\Services\JwtAuth;
use BackendBundle\DataFixtures\ORM\LoadUsergroupData;
use BackendBundle\Entity\User;
use BackendBundle\Repository\UsergroupRepository;
use BackendBundle\Repository\UserRepository;
use BackendBundle\Services\UserServices;
use Doctrine\ORM\EntityManager;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;

class UsergroupControllerTest extends WebTestCase
{
    private $jwt;
    private $jwtWithGroup;
    private $wrongJwt = 'wrong';
    /** @var JwtAuth $jwtService */
    private $jwtAuthService;
    /** @var UsergroupRepository $userRep */
    private $usergroupRep;
    /** @var UserRepository $userRep */
    private $userRep;
    /** @var User $user */
    private $currentUser;
    /** @var User $userWithGroup */
    private $userWithGroup;
    /** @var EntityManager $em */
    private $em;
    /** @var Client $client */
    private $client;
    /** @var UserServices $client */
    private $userService;

    public function setUp()
    {
        $this->jwtAuthService = $this->getContainer()->get('app.jwt_auth');
        $this->userRep = $this->getContainer()->get('doctrine')->getManager()
            ->getRepository('BackendBundle:User');
        $this->usergroupRep = $this->getContainer()->get('doctrine')->getManager()
            ->getRepository('BackendBundle:Usergroup');
        $this->em = $this->getContainer()->get('doctrine')->getManager();
        $this->userService = $this->getContainer()->get('user.user');

        $fixtures = array('BackendBundle\DataFixtures\ORM\LoadUsergroupData');
        $this->loadFixtures($fixtures);
        $users = LoadUsergroupData::$users;
        $this->currentUser = $users[0];
        $this->userWithGroup = $users[1];

        $this->jwt = $this->jwtAuthService->signup(
            $this->currentUser->getEmail(),
            $this->currentUser->getPassword(),
            true
        );
        $this->jwtWithGroup = $this->jwtAuthService->signup(
            $this->userWithGroup->getEmail(),
            $this->userWithGroup->getPassword(),
            true
        );

        $reflect = new \ReflectionClass($this->userWithGroup->getUsergroup());
        $nbProps = count($reflect->getProperties());
        $this->nbProperties = $nbProps;

        $this->client = static::createClient();
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

    public function testNewActionInvalidAuth()
    {
        $this->toTestFailedCase('POST', 401, 'invalid_authorization', array('json' => ''), 'Test INVALID auth (in create)', 'new_usergroup', $this->wrongJwt);
        $this->toTestFailedCase('POST', 401, 'invalid_authorization', array('json' => ''), 'Test INVALID auth (in create)', 'new_usergroup');
    }

    public function testNewActionWrongParams()
    {
        $this->toTestFailedCase('POST', 400, 'name_null', array('json' => '{}'), 'Wrong Data Test (in create)', 'new_usergroup', true);
        $this->toTestFailedCase('POST', 400, 'without_json', false, 'Wrong Data Test (in create)', 'new_usergroup', true);
    }

    public function testNewActionUserHasAlreadyGroup()
    {
        $json = '{"name":"username"}';
        $this->toTestFailedCase('POST', 409, 'user_with_group', array('json' => $json), 'User has already a group', 'new_usergroup', $this->jwtWithGroup);
    }

    public function testNewActionOk()
    {
        $this->client->request(
            'POST',
            $this->getUrl('new_usergroup'),
            array('json' => '{"name":"new group name"}'),
            array(),
            array(
                'CONTENT_TYPE'          => 'application/json',
                'HTTP_Authorization'    =>  $this->jwt,
                'HTTP_X-Requested-With' => 'XMLHttpRequest',
            )
        );
        $response = $this->client->getResponse();

        /**************************************** ASSERTS ***********************************************/

        $this->assertJsonResponse($response, 200);
        $arrResponse = json_decode($response->getContent(), true);
        $this->assertEquals($arrResponse['code'], 200, 'OK test new group -> Recieved message:'.print_r($arrResponse['msg'], true));


        /* Control that there weren't new properties to the Class added (otherwise, the properties should be tested too) */
        $this->assertEquals($this->nbProperties, 5, 'It were added new properties to the User entity. These should be tested');

        /** @var User $currentUser */
        $currentUser = $this->userRep->findOneBy(
            array('id' => $this->currentUser->getId())
        );
        $this->em->refresh($currentUser);

        $createdUsergroup = $currentUser->getUsergroup();
        // Check if the rights properties were updated
        $this->assertEquals($createdUsergroup->getName(), 'new group name', 'usergroup\'s name failed');
        $this->assertNotNull($createdUsergroup->getDateLastEdited(), 'DateLastEdited is null');
        $this->assertNotNull($createdUsergroup->getDateValidFrom(), 'DateValidFrom is null');
        // Tests if the rest of the properties were not updated (they shouldn't)
        // $updatedUser == $this->currentUser in all properties
        $this->assertNull($createdUsergroup->getDateValidTo(), 'DateValidTo should not be updated');
    }

    public function testEditActionInvalidAuth()
    {
        $this->toTestFailedCase('POST', 401, 'invalid_authorization', array('json' => ''), 'Test INVALID auth (in create)', 'edit_usergroup', $this->wrongJwt);
        $this->toTestFailedCase('POST', 401, 'invalid_authorization', array('json' => ''), 'Test INVALID auth (in create)', 'edit_usergroup');
    }

    public function testEditActionUserHasNoGroupToEdit()
    {
        $json = '{"name":"username"}';
        $this->toTestFailedCase('POST', 404, 'user_without_group', array('json' => $json), 'User has any group', 'edit_usergroup', true);
    }

    public function testEditActionWrongParams()
    {
        $this->toTestFailedCase('POST', 400, 'name_null', array('json' => '{}'), 'Wrong Data Test (in edit)', 'edit_usergroup',  $this->jwtWithGroup);
        $this->toTestFailedCase('POST', 400, 'user_without_group', false, 'User has any group', 'edit_usergroup', $this->jwtWithGroup);
    }

    public function testEditActionOk()
    {
        $this->client->request(
            'POST',
            $this->getUrl('edit_usergroup'),
            array('json' => '{"name":"new group name"}'),
            array(),
            array(
                'CONTENT_TYPE'          => 'application/json',
                'HTTP_Authorization'    =>  $this->jwtWithGroup,
                'HTTP_X-Requested-With' => 'XMLHttpRequest',
            )
        );
        $response = $this->client->getResponse();

        /**************************************** ASSERTS ***********************************************/

        $this->assertJsonResponse($response, 200);
        $arrResponse = json_decode($response->getContent(), true);
        $this->assertEquals($arrResponse['code'], 200, 'OK test edit group -> Recieved message:'.print_r($arrResponse['msg'], true));


        /* Control that there weren't new properties to the Class added (otherwise, the properties should be tested too) */
        $this->assertEquals($this->nbProperties, 5, 'It were added new properties to the User entity. These should be tested');

        /** @var User $currentUser */
        $currentUserWithGroup = $this->userRep->findOneBy(
            array('id' => $this->userWithGroup->getId())
        );
        $this->em->refresh($currentUserWithGroup);

        $currentUsergroup = $this->userWithGroup->getUsergroup();
        $updatedUsergroup = $currentUserWithGroup->getUsergroup();
        // Check if the rights properties were updated
        $this->assertEquals($updatedUsergroup->getName(), 'new group name', 'usergroup\'s name not updated');
        $this->assertNotEquals($updatedUsergroup->getDateLastEdited(), $currentUsergroup->getDateLastEdited(), 'DateLastEdited not updated');
        // Tests if the rest of the properties were not updated (they shouldn't)
        // $updatedUser == $this->currentUser in all properties
        $this->assertEquals($updatedUsergroup->getDateValidFrom(), $currentUsergroup->getDateValidFrom(), 'DateValidFrom should not be updated');
        $this->assertEquals($updatedUsergroup->getDateValidTo(), $currentUsergroup->getDateValidTo(), 'DateValidTo should not be updated');
    }
}
