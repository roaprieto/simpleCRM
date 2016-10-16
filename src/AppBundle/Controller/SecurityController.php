<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Email;

class SecurityController extends Controller
{
    /**
     * login action
     *
     * @Route("/login", name="login")
     * @Method("POST")
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function loginAction(Request $request)
    {
        $helpers = $this->get("app.helpers");
        $jwtAuth = $this->get("app.jwt_auth");

        $json = $request->get("json", null);

        if ($json != null) {
            $params = json_decode($json);

            $email = (isset($params->email)) ? $params->email : null;
            $password = (isset($params->password)) ? $params->password : null;
            $getHash = (isset($params->gethash)) ? $params->gethash : null;

            $emailConstraint = new Email();
            $emailConstraint->message = "This email is not valid";
            $validateEmail = $this->get("validator")->validate($email, $emailConstraint);

            $pwd = hash('sha512', $password);

            if (count($validateEmail) == 0 && $password != null && $email != null) {
                if ($getHash == null) {
                    $signup = $jwtAuth->signup($email, $pwd);
                } else {
                    $signup = $jwtAuth->signup($email, $pwd, true);
                }
                if ($signup === false) {
                    $data = array(
                        'status' => 'error',
                        'msg' => 'Invalid credentials. User doesn\'t exist',
                        'code' => 404
                    );
                } else {
                    return new JsonResponse($signup);
                }
            } else {
                $data = array(
                    'status' => 'error',
                    'msg' => 'Wrong parameters sent',
                    'code' => 400
                );
            }
        } else {
            $data = array(
                'status' => 'error',
                'msg' => 'Wrong parameters sent',
                'code' => 400
            );
        }
        return $helpers->json($data);
    }
}
