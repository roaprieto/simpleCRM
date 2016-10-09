<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Email;

class DefaultController extends Controller
{
    /**
     * @Route("/login", name="login")
     * @Method("Post")
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

            // Cifrar password
            $pwd = hash('sha256', $password);

            if (count($validateEmail) == 0 && $password != null) {
                if ($getHash == null) {
                    $signup = $jwtAuth->signup($email, $pwd);
                } else {
                    $signup = $jwtAuth->signup($email, $pwd, true);
                }
                return new JsonResponse($signup);
            } else {
                return $helpers->json(array(
                    "status" => "error",
                    "data" => "Login not valid!"
                ));
            }

        } else {
            echo "send json with post";
        }

        die();

    }


    /**
     * @Route("/pruebas", name="pruebas")
     */
    public function pruebasAction(Request $request)
    {
        $helpers = $this->get("app.helpers");
        $hash = $request->get("authorization", null);
        $check = $helpers->authCheck($hash, true);

        var_dump($check);
        die();


        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('BackendBundle:User')->findAll();

	   return $helpers->json($users);
    }
}
