<?php

namespace AppBundle\Services;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


class Helpers
{
    private $_jwtAuth;
    private $_validator;

    public function __construct(
        JwtAuth $jwtAuth,
        ValidatorInterface $validator
    ) {
        $this->_jwtAuth = $jwtAuth;
        $this->_validator = $validator;
    }

    /**
     * Check if the authorization is valid (Takes the hash and check the token)
     *
     * @param $hash
     * @param bool $getIdentity
     *
     * @return bool|object "false" if the authorization is invalid. If the authorization is valid, it returns "true" if
     * $getIdentity = false or the identity itself if $getIdentity != false
     */
    public function authCheck($hash, $getIdentity = false)
    {
        $jwtAuth = $this->_jwtAuth;

        $auth = false;
        if ($hash != null) {
            if ($getIdentity == false) {
                $checkToken = $jwtAuth->checkToken($hash);
                if ($checkToken == true) {
                    $auth = true;
                }
            } else {
                $checkToken = $jwtAuth->checkToken($hash, true);
                if (is_object($checkToken)) {
                    $auth = $checkToken;
                }
            }
        }

        return $auth;
    }

    /**
     * serialize an array
     *
     * @param array $data data to be serialized
     *
     * @return \Symfony\Component\HttpFoundation\Response $response with the serialized data
     */
    public function json($data)
    {
        $normalizers = array(new GetSetMethodNormalizer());
        $encoders = array("json" => new JsonEncoder());

        $serializer = new Serializer($normalizers, $encoders);
        $json = $serializer->serialize($data, 'json');

        $response = new Response();
        $response->setContent($json);
        $response->headers->set("Content-Type", "application/json");

        return $response;
    }

    /**
     * Validate an entity using for this the validation rules of Symfony
     *
     * @param $entity
     *
     * @return array|bool with the errors if there is some or false if there is any
     */
    public function validateWrongDataInEntity($entity)
    {
        $errors = $this->_validator->validate($entity);
        $errorMsg = false;
        if (count($errors) > 0) {
            $errorMsg = array();
            foreach ($errors as $error) {
                $errorMsg[$error->getPropertyPath()] = $error->getMessage();
            }
        }
        return $errorMsg;
    }

    /**
     * Check if the values in the array $fieldToTest are already recorded in the database
     *
     * @param object $entity      Entity to be valided
     * @param array  $fieldToTest Fields' names to be valided, if they are repeated in the database
     *
     * @return array|bool with "repeated" errors if there is some or false if there is any
     */
    public function validateRepeatedDataInEntity($entity, $fieldToTest)
    {
        $repeatedErrorMsg = false;
        foreach ($fieldToTest as $paramToTest) {
            $repeatedErrors = $this->_validator->validate($entity, new UniqueEntity($paramToTest));
            if (count($repeatedErrors) > 0) {
                $repeatedErrorMsg = array();
                foreach ($repeatedErrors as $error) {
                    $repeatedErrorMsg[$error->getPropertyPath()] = $error->getMessage();
                }
            }
        }
        return $repeatedErrorMsg;
    }
}