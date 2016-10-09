<?php

namespace AppBundle\Services;

use Firebase\JWT\JWT;

class JwtAuth
{
    private $em;
    private $key;

    public function __construct($em)
    {
        $this->em = $em;
        $this->key = "clave-secreta";
    }

    public function signup($email, $password, $getHash = NULL)
    {

        $user = $this->em->getRepository("BackendBundle:User")->findOneBy(
            array(
                "email" => $email,
                "password" => $password
            )
        );

        $signup = false;
        if (is_object($user)) {
            $signup = true;
        }


        if ($signup == true) {

            $token = array(
                "sub" => $user->getId(),
                "username" => $user->getUsername(),
                "email" => $user->getEmail(),
                "name" => $user->getPassword(),
                "iat" => time(),
                "exp" => time() + (7*24*60*60)
            );

            $jwt = JWT::encode($token, $this->key, 'HS256');
            $decoded = JWT::decode($jwt, $this->key, array('HS256'));

            if ($getHash != null) {
                return $jwt;
            } else {
                return $decoded;
            }
        } else {
            return array("status" => "error", "data" => "Login failed!");
        }
    }

    public function checkToken($jwt, $getIdentity = false)
    {
        $key = $this->key;
        $auth = false;

        try {
            $decoded = JWT::decode($jwt, $key, array('HS256'));
        } catch(\UnexpectedValueException $e) {
            $auth = false;
        } catch(\DomainException $e) {
            $auth = false;
        }

        if (isset($decoded->sub)) {
            $auth = true;
        } else {
            $auth = false;
        }

        if ($getIdentity) {
            return $decoded;
        } else {
            return $auth;
        }


    }
}