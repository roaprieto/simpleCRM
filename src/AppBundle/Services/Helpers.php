<?php

namespace AppBundle\Services;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;

class Helpers
{
    public $jwtAuth;

    public function __construct($jwtAuth)
    {
        $this->jwtAuth = $jwtAuth;
    }

    public function authCheck($hash, $getIdentity = false)
    {
        $jwtAuth = $this->jwtAuth;

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
     * @return jsonResponse with the serialized data
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
}