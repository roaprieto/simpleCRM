<?php
/**
 * Created by PhpStorm.
 * User: francisco
 * Date: 09.10.16
 * Time: 19:45
 */

namespace BackendBundle\Controller;


use BackendBundle\Entity\User;
use BackendBundle\Entity\Usergroup;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/usergroup")
 */
class UsergroupController extends Controller
{
    /**
     * @Route("/new", name="new")
     * @Method("POST")
     */
    public function newAction(Request $request) {
        $helpers = $this->get("app.helpers");

        $hash = $request->get("authorization", null);
        $authCheck = $helpers->authCheck($hash);

        if ($authCheck == true) {
            $identity = $helpers->authCheck($hash, true);

            $json = $request->get("json", null);

            if ($json != null) {
                $params = json_decode($json);

                $createdAt = new \Datetime('now');
                $updatedAt = new \Datetime('now');
                $imagen = null;
                $video_path = null;

                $user_id = ($identity->sub != null) ? $identity->sub : null;
                $title = (isset($params->title)) ? $params->title : null;
                $description = (isset($params->description)) ? $params->description : null;
                $status = (isset($params->status)) ? $params->status : null;

                if ($user_id != null && $title != null) {
                    $em = $this->getDoctrine()->getManager();

                    $user = $em->getRepository("BackendBundle:User")->findOneBy(
                        array(
                            "id" => $user_id
                        ));

                    $usergroup = new Usergroup();
                    $usergroup->setDateLastEdited($updatedAt);
                    $usergroup->getDateValidFrom($createdAt);
                    $usergroup->setName($name);
                    $usergroup->setUser($user);

                    $em->persist($video);
                    $em->flush();

                    $video = $em->getRepository("BackendBundle:Video")->findOneBy(
                        array(
                            "user" => $user,
                            "title" => $title,
                            "status" => $status,
                            "createdAt" => $createdAt
                        ));

                    $data = array(
                        "status" => "success",
                        "code" => 200,
                        "data" => $video
                    );
                } else {
                    $data = array(
                        "status" => "error",
                        "code" => 400,
                        "msg" => "Video not created"
                    );
                }
            } else {
                $data = array(
                    "status" => "error",
                    "code" => 400,
                    "msg" => "Video not created, params failed"
                );
            }
        } else {
            $data = array(
                "status" => "error",
                "code" => 400,
                "msg" => "Authorization not valid"
            );
        }

        return $helpers->json($data);
    }
    /**
     * @Route("/", name="")
     * @Method("GET")
     */
    public function indexAction(Request $request) {
        return new \Symfony\Component\HttpFoundation\JsonResponse('Hello World');
    }

}