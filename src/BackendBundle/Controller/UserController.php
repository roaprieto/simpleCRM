<?php
/**
 * Created by PhpStorm.
 * User: francisco
 * Date: 09.10.16
 * Time: 19:45
 */

namespace BackendBundle\Controller;


use BackendBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/user")
 */
class UserController extends Controller
{
    /**
     * @Route("/new", name="new")
     * @Method("Post")
     */
    public function newAction(Request $request)
    {
        $helpers = $this->get("app.helpers");

        $json = $request->get("json", null);
        $params = json_decode($json);

        $data = array(
            "status" => "error",
            "code" => 400,
            "msg" => "User not created"
        );

        if ($json != null) {
            $createdAt = new \Datetime("now");

            $email = (isset($params->email)) ? $params->email : null;
            $username = (isset($params->username) && ctype_alpha($params->username)) ? $params->username : null;
            $password = (isset($params->password)) ? $params->password : null;

            $emailContraint = new Email();
            $emailContraint->message = "This email is not valid !!";
            $validate_email = $this->get("validator")->validate($email, $emailContraint);

            if ($email != null && count($validate_email) == 0 &&
                $password != null && $username != null
            ) {
                $user = new User();
                $user->setDateLastEdited($createdAt);
                $user->setDateValidFrom($createdAt);
                $user->setEmail($email);
                $user->setUsername($username);
                $user->setProfilePicture('profileimg-placeholder.jpg');

                //Cifrar la password
                $pwd = hash('sha256', $password);
                $user->setPassword($pwd);

                $em = $this->getDoctrine()->getManager();
                $isset_user = $em->getRepository("BackendBundle:User")->findBy(
                    array(
                        "email" => $email
                    ));

                if (count($isset_user) == 0) {
                    $em->persist($user);
                    $em->flush();

                    $data["status"] = 'success';
                    $data["code"] = 200;
                    $data["msg"] = 'New user created !!';
                } else {
                    $data = array(
                        "status" => "error",
                        "code" => 400,
                        "msg" => "User not created, duplicated!!"
                    );
                }
            }
        }

        return $helpers->json($data);
    }

    /**
     * @Route("/edit", name="edit")
     * @Method("Post")
     */
    public function editAction(Request $request) {
        $helpers = $this->get("app.helpers");

        $hash = $request->get("authorization", null);
        $authCheck = $helpers->authCheck($hash);

        if ($authCheck == true) {

            $identity = $helpers->authCheck($hash, true);

            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository("BackendBundle:User")->findOneBy(array(
                "id" => $identity->sub
            ));

            $json = $request->get("json", null);
            $params = json_decode($json);

            $data = array(
                "status" => "error",
                "code" => 400,
                "msg" => "User not updated"
            );

            if ($json != null) {
                $createdAt = new \Datetime("now");
                $image = null;
                $role = "user";

                $email = (isset($params->email)) ? $params->email : null;
                $username = (isset($params->username) && ctype_alpha($params->username)) ? $params->username : null;
                $password = (isset($params->password)) ? $params->password : null;

                $emailContraint = new Email();
                $emailContraint->message = "This email is not valid !!";
                $validate_email = $this->get("validator")->validate($email, $emailContraint);

                if ($email != null && count($validate_email) == 0 &&
                    $username != null
                ) {
                    $user->setDateLastEdited($createdAt);
                    $user->setDateValidFrom($createdAt);
                    $user->setEmail($email);
                    $user->setUsername($username);

                    if($password != null && !empty($password)){
                        //Cifrar la password
                        $pwd = hash('sha256', $password);
                        $user->setPassword($pwd);
                    }

                    $em = $this->getDoctrine()->getManager();
                    $isset_user = $em->getRepository("BackendBundle:User")->findBy(
                        array(
                            "email" => $email
                        ));

                    if (count($isset_user) == 0 || $identity->email == $email) {
                        $em->persist($user);
                        $em->flush();

                        $data["status"] = 'success';
                        $data["code"] = 200;
                        $data["msg"] = 'User updated !!';
                    } else {
                        $data = array(
                            "status" => "error",
                            "code" => 400,
                            "msg" => "User not updated, duplicated!!"
                        );
                    }
                }
            } else {
                $data = array(
                    "status" => "error",
                    "code" => 400,
                    "msg" => "Authorization not valid"
                );
            }
        }

        return $helpers->json($data);
    }

    /**
     * @Route("/upload_image", name="upload_image")
     * @Method("Post")
     */
    public function uploadImageAction(Request $request){
        $helpers = $this->get("app.helpers");

        $hash = $request->get("authorization", null);
        $authCheck = $helpers->authCheck($hash);

        if($authCheck){
            $identity = $helpers->authCheck($hash, true);

            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository("BackendBundle:User")->findOneBy(array(
                "id" => $identity->sub
            ));

            // upload file
            $file = $request->files->get("image");

            if(!empty($file) && $file != null){
                $ext = $file->guessExtension();

                if($ext == "jpeg" || $ext == "jpg" ||
                    $ext == "png" || $ext == "gif"){

                    $file_name = $user->getId().".".$ext;
                    $file->move("uploads/users", $file_name);

                    $user->setProfilePicture($file_name);
                    $em->persist($user);
                    $em->flush();

                    $data = array(
                        "status" => "success",
                        "code"	 => 200,
                        "msg"	 => "Image for user uploaded success !!"
                    );
                }else{
                    $data = array(
                        "status" => "error",
                        "code"	 => 400,
                        "msg"	 => "File not valid!!"
                    );
                }
            }else{
                $data = array(
                    "status" => "error",
                    "code"	 => 400,
                    "msg"	 => "Image not uploaded"
                );
            }

        }else{
            $data = array(
                "status" => "error",
                "code"	 => 400,
                "msg"	 => "Authorization not valid"
            );
        }

        return $helpers->json($data);
    }

}