<?php

namespace AppBundle\Services;

use Doctrine\ORM\EntityManager;
use Firebase\JWT\JWT;

class JwtAuth
{
    private $em;
    private $key;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->key = "o38vUH(nNFG_S__-eg-sdh";
    }

    /**
     * Sing a user up
     *
     * @param $email
     * @param $password
     * @param null $getHash
     *
     * @return bool|object|string returns "false" if there no user with these credentials (email + password).
     * If there is a user with the credentials, returns the identity (the user) if $getHash = null. Otherwise returns
     * the JWT
     */
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
            return false;
        }
    }

    /**
     * Check if the token §jwt is valid
     *
     * @param $jwt
     * @param bool $getIdentity
     *
     * @return bool|object returns "false" if the token is not valid or there is not user with this token.
     * Returns "true" if $getIdentity = false or the user entity if $getIdentity != false
     */
    public function checkToken($jwt, $getIdentity = false)
    {
        $key = $this->key;

        try {
            $decoded = JWT::decode($jwt, $key, array('HS256'));
        } catch(\UnexpectedValueException $e) {
            return false;
        } catch(\DomainException $e) {
            return false;
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