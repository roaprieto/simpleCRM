<?php
/**
 * Created by PhpStorm.
 * User: francisco
 * Date: 11.10.16
 * Time: 11:31
 */

namespace tests\BackendBundle\Controller;


use AppBundle\Services\JwtAuth;
use BackendBundle\DataFixtures\ORM\LoadUserData;
use BackendBundle\Entity\User;
use BackendBundle\Repository\UserRepository;
use BackendBundle\Services\UserServices;
use Doctrine\ORM\EntityManager;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UserControllerTest extends WebTestCase
{
    private $jwt;
    private $nbProperties;
    /** @var JwtAuth $jwtService */
    private $jwtAuthService;
    /** @var UserRepository $userRep */
    private $userRep;
    /** @var User $user */
    private $currentUser;
    /** @var User $user */
    private $anotherUser;
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
        $this->em = $this->getContainer()->get('doctrine')->getManager();
        $this->userService = $this->getContainer()->get('user.user');

        $fixtures = array('BackendBundle\DataFixtures\ORM\LoadUserData');
        $this->loadFixtures($fixtures);
        $users = LoadUserData::$users;
        $this->currentUser = $users[0];
        $this->anotherUser = $users[1];

        $this->jwt = $this->jwtAuthService->signup(
            $this->currentUser->getEmail(),
            $this->currentUser->getPassword(),
            true
        );

        $reflect = new \ReflectionClass($this->currentUser);
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



    public function testNewAction()
    {
        /************************************************************************************************/
        /************************************* TEST DUPLICATE DATA **************************************/
        /************************************************************************************************/
        $jsonRepeatedParams = array(
            'username_repeated' => '{"email":"newemail@email.com", "username":"'.$this->currentUser->getUsername().'", "password":"admin"}',
            'email_repeated' => '{"email":"'.$this->currentUser->getEmail().'", "username":"FranciscoRoa", "password":"franroa"}'
        );
        foreach ($jsonRepeatedParams as $paramTestName => $jsonValue) {
            $this->toTestFailedCase('POST', 409, $paramTestName, array('json' => $jsonValue), 'Repeated Values Test (in create)', 'new_user');
        }


        /************************************************************************************************/
        /*************************************** TEST WRONG DATA ****************************************/
        /************************************************************************************************/
        $jsonFailedParams = array(
            'username_null' => '{"email":"test_new_user@email.com", "password":"franroa"}',
            'short_username' => '{"email":"test_new_usmail.com", "username":"Fr","password":"franroa"}',
            'email_null' => '{"username":"FranciscoRoa","password":"franroa"}',
            'wrong_email_format' => '{"email":"test_new_usmail.com", "username":"FranciscoRoa","password":"franroa"}',
            'password_null' => '{"email":"test_new_user@email.com","username":"FranciscoRoa"}'
        );
        foreach ($jsonFailedParams as $paramTestName => $jsonValue) {
            $this->toTestFailedCase('POST', 400, $paramTestName, array('json' => $jsonValue), 'Wrong Data Test (in create)', 'new_user');
        }


        /************************************************************************************************/
        /************************************************************************************************/
        /********************************************** OK **********************************************/
        /************************************************************************************************/
        /************************************************************************************************/
        $this->client->request(
            'POST',
            $this->getUrl('new_user'),
            array('json' => '{"email":"test_new_user@email.com","username":"FranciscoRoa","password":"franroa"}'),
            array(),
            array(
                'CONTENT_TYPE'          => 'application/json',
                'HTTP_X-Requested-With' => 'XMLHttpRequest',
            )
        );

        $response = $this->client->getResponse();
        $arrResponse = json_decode($response->getContent(), true);

        /** @var User $createdUser */
        $createdUser = $this->userRep->findOneBy(
            array('email' => 'test_new_user@email.com')
        );
        $this->em->refresh($createdUser);

        /**************************************** ASSERTS ***********************************************/

        $this->assertJsonResponse($response, 200);
        $this->assertEquals($arrResponse['code'], 200, 'OK Test created -> Recieved message:'.print_r($arrResponse['msg'], true));


        /* Control that there weren't new properties to the Class added (otherwise, the properties should be tested too) */
        $this->assertEquals($this->nbProperties, 10, 'It were added new properties to the User entity. These should be tested');

        // Check if the rights properties were updated
        $this->assertEquals($createdUser->getEmail(), 'test_new_user@email.com', 'email failed');
        $this->assertEquals($createdUser->getUsername(), 'FranciscoRoa', 'Username failed');
        $this->assertEquals($createdUser->getPassword(), hash('sha512', 'franroa'), 'Password failed');
        $this->assertEquals(
            $createdUser->getProfilePicture(),
            $this->userService->getProfileImg(),
            'ProfilePicture failed'
        );
        $this->assertNotNull($createdUser->getDateLastEdited(), 'DateLastEdited is null');
        $this->assertNotNull($createdUser->getDateValidFrom(), 'DateValidFrom is null');
        // Tests if the rest of the properties were not updated (they shouldn't)
        // $updatedUser == $this->currentUser in all properties
        $this->assertNull($createdUser->getDateValidTo(), 'DateValidTo should not be updated');
        $this->assertNull($createdUser->getUsergroup(), 'Usergroup should not be updated');
        $this->assertEquals(0, $createdUser->getOwner(), 'Owner should not be changed');
    }


    /**
     * Test editAction
     */
    public function testEditAction()
    {
        $emailUpdated = 'email@email.com';

        /************************************************************************************************/
        /********************************* TEST INVALID AUTHORIZATION ***********************************/
        /************************************************************************************************/
        $json = '{"email":"'.$emailUpdated.'","username":"admin","password":"admin"}';
        $wrongJwt = 'wrong';
        $this->toTestFailedCase('POST', 401, 'invalid_authorization', array('json' => $json), 'Test INVALID auth (in edit)', 'edit_user', $wrongJwt);
        $this->toTestFailedCase('POST', 401, 'invalid_authorization', array('json' => $json), 'Test INVALID auth (in edit)', 'edit_user');


        /************************************************************************************************/
        /************************************* TEST DUPLICATE DATA **************************************/
        /************************************************************************************************/
        $jsonRepeatedParams = array(
            'username_repeated' => '{"email":"newemail@email.com", "username":"'.$this->anotherUser->getUsername().'", "password":"admin"}',
            'email_repeated' => '{"email":"'.$this->anotherUser->getEmail().'", "username":"FranciscoRoa", "password":"franroa"}'
        );
        foreach ($jsonRepeatedParams as $paramTestName => $jsonValue) {
            $this->toTestFailedCase('POST', 409, $paramTestName, array('json' => $jsonValue), 'Repeated Values Test (in edit)', 'edit_user', true);
        }


        /************************************************************************************************/
        /*************************************** TEST WRONG DATA ****************************************/
        /************************************************************************************************/
        $jsonFailedParams = array(
            'username_null' => '{"email":"test_new_user@email.com", "password":"franroa"}',
            'email_null' => '{"username":"FranciscoRoa","password":"franroa"}',
            'wrong_email_format' => '{"email":"test_new_usmail.com", "username":"FranciscoRoa","password":"franroa"}',
            'password_null' => '{"email":"test_new_user@email.com","username":"FranciscoRoa"}'
        );

        foreach ($jsonFailedParams as $paramTestName => $jsonValue) {
            $this->toTestFailedCase('POST', 400, $paramTestName, array('json' => $jsonValue), 'Wrong Values Test (in edit)', 'edit_user', true);
        }



        /************************************************************************************************/
        /************************************************************************************************/
        /***************************************** TEST OK **********************************************/
        /************************************************************************************************/
        /************************************************************************************************/
        $this->client->request(
            'POST',
            $this->getUrl('edit_user'),
            array('json' => '{"email":"'.$emailUpdated.'","username":"admin","password":"admin"}'),
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
        $this->assertEquals($arrResponse['code'], 200, 'OK test edit -> Recieved message:'.print_r($arrResponse['msg'], true));


        /* Control that there weren't new properties to the Class added (otherwise, the properties should be tested too) */
        $this->assertEquals($this->nbProperties, 10, 'It were added new properties to the User entity. These should be tested');


        /** @var User $updatedUser */
        $updatedUser = $this->userRep->findOneBy(
            array('email' => $emailUpdated)
        );
        $this->em->refresh($updatedUser);


        // Check if the rights properties were updated
        $this->assertEquals($updatedUser->getEmail(), $emailUpdated, 'Email wasn\'t updated');
        $this->assertEquals($updatedUser->getUsername(), 'admin', 'Username wasn\'t updated');
        $this->assertEquals($updatedUser->getPassword(), hash('sha512', 'admin'), 'Password wasn\'t updated');
        $this->assertGreaterThan(
            $this->currentUser->getDateLastEdited(),
            $updatedUser->getDateLastEdited(),
            'DateLastEdited wasn\'t updated'
        );
        // Tests if the rest of the properties were not updated (they shouldn't)
        // $updatedUser == $this->currentUser in all properties
        $this->assertEquals(0, $updatedUser->getOwner(), 'Owner should not be changed');
        $this->assertEquals(
            $this->currentUser->getDateValidTo(),
            $updatedUser->getDateValidTo(),
            'DateValidTo was updated and should not be updated'
        );
        $this->assertEquals(
            $this->currentUser->getDateValidFrom(),
            $updatedUser->getDateValidFrom(),
            'DateValidFrom was updated and should not be updated'
        );
        $this->assertEquals(
            $this->currentUser->getUsergroup(),
            $updatedUser->getUsergroup(),
            'Usergroup was updated and should not be updated'
        );
        $this->assertEquals(
            $this->currentUser->getProfilePicture(),
            $updatedUser->getProfilePicture(),
            'ProfilePicture was updated and should not be updated'
        );
    }


    /*
     * test uploadImageAction
     */
    public function testUploadImageAction()
    {
        /************************************************************************************************/
        /********************************** TEST WRONG AUTHORIZATION ************************************/
        /************************************************************************************************/
        // File's original copy. Otherwise would be move (remove) form the folder, and the test wouldn't work again
        $imgPath = __DIR__."/Files/user_profile_image.jpg";
        $imgPathAux = __DIR__."/Files/aux.jpg";
        copy($imgPath, $imgPathAux);
        $photo = new UploadedFile($imgPathAux, 'test.jpg', 'image/jpeg', 123);
        $wrongJwt = 'wrong';
        $this->toTestFailedCase('POST', 401, 'invalid_authorization', false, 'INVALID auth (in upload)', 'upload_user_profile_image', $wrongJwt, array('image' => $photo));
        $this->toTestFailedCase('POST', 401, 'invalid_authorization', false, 'INVALID auth (in upload)', 'upload_user_profile_image', false, array('image' => $photo));


        /************************************************************************************************/
        /*************************************** TEST WRONG DATA ****************************************/
        /************************************************************************************************/
        // Test a wrong file extension (with pdf)
        $imgPath = __DIR__."/Files/iterin_report.pdf";
        $photo = new UploadedFile($imgPath, 'test.jpg', 'image/jpeg', 123);
        $this->toTestFailedCase('POST', 400, 'wrong_image_format', false, 'Wrong Values Test (upload)', 'upload_user_profile_image', true, array('image' => $photo));



        /************************************************************************************************/
        /************************************************************************************************/
        /***************************************** TEST OK **********************************************/
        /************************************************************************************************/
        /************************************************************************************************/
        // File's original copy. Otherwise would be move (remove) form the folder, and the test wouldn't work again
        $imgPath = __DIR__."/Files/user_profile_image.jpg";
        $imgPathAux = __DIR__."/Files/aux.jpg";
        copy($imgPath, $imgPathAux);

        $photo = new UploadedFile($imgPathAux, 'test.jpg', 'image/jpeg', 123);

        $this->client->request(
            'POST',
            $this->getUrl('upload_user_profile_image'),
            array(),
            array('image' => $photo),
            array(
                'CONTENT_TYPE'          => 'application/json',
                'HTTP_Authorization'    =>  $this->jwt,
                'HTTP_X-Requested-With' => 'XMLHttpRequest',
            )
        );
        $response = $this->client->getResponse();
        $arrResponse = json_decode($response->getContent(), true);

        /**************************************** ASSERTS ***********************************************/

        $this->assertEquals($arrResponse['code'], 200, 'OK test upload -> Recieved message:'.print_r($arrResponse['msg'], true));

        /* Control that there weren't new properties to the Class added (otherwise, the properties should be tested too) */
        $this->assertEquals($this->nbProperties, 10, 'It were added new properties to the User entity. These should be tested');


        /** @var User $updatedUser */
        $updatedUser = $this->userRep->findOneBy(
            array('id' => $this->currentUser->getId())
        );
        $this->em->refresh($updatedUser);

        // Check if the rights properties were updated
        $this->assertGreaterThan(
            $this->currentUser->getDateLastEdited(),
            $updatedUser->getDateLastEdited(),
            'DateLastEdited wasn\'t updated'
        );
        $this->assertNotEquals(
            $this->currentUser->getProfilePicture(),
            $this->userService->getProfileImg(),
            'ProfilePicture was updated and should not be updated'
        );
        // Tests if the rest of the properties were not updated (they shouldn't)
        // $updatedUser == $this->currentUser in all properties
        $this->assertEquals(0, $updatedUser->getOwner(), 'Owner should not be changed');
        $this->assertEquals(
            $this->currentUser->getEmail(),
            $updatedUser->getEmail(),
            'Email was updated and should not be updated'
        );
        $this->assertEquals(
            $this->currentUser->getUsername(),
            $updatedUser->getUsername(),
            'Username was updated and should not be updated'
        );
        $this->assertEquals(
            $this->currentUser->getUsergroup(),
            $updatedUser->getUsergroup(),
            'Usergroup was updated and should not be updated'
        );
        $this->assertEquals(
            $this->currentUser->getDateValidTo(),
            $updatedUser->getDateValidTo(),
            'DateValidTo was updated and should not be updated'
        );
        $this->assertEquals(
            $this->currentUser->getDateValidFrom(),
            $updatedUser->getDateValidFrom(),
            'DateValidFrom was updated and should not be updated'
        );
        $this->assertEquals(
            $this->currentUser->getPassword(),
            $updatedUser->getPassword(),
            'Password was updated and should not be updated'
        );
    }
}