<?php

namespace BackendBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/user")
 */
class UserController extends Controller
{
    /**
     * creates a new user
     *
     * @Route("/new", name="new_user")
     * @Method("POST")
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function newAction(Request $request)
    {
        $helpers = $this->get("app.helpers");
        $json = $request->get("json", null);
        $data = $this->get('user.user')->addNewUser($json);
        return $helpers->json($data);
    }

    /**
     * edits user action
     *
     * @Route("/edit", name="edit_user")
     * @Method("POST")
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function editAction(Request $request)
    {
        $helpers = $this->get("app.helpers");
        $hash = $request->headers->get('authorization', null);
        $json = $request->get("json", null);
        $data = $this->get('user.user')->editUserFromIdentityWithParams(
            $hash,
            $json
        );
        return $helpers->json($data);
    }

    /**
     * uploads a profile image
     *
     * @Route("/upload_image", name="upload_user_profile_image")
     * @Method("POST")
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function uploadImageAction(Request $request)
    {
        $helpers = $this->get("app.helpers");
        $hash = $request->headers->get('authorization', null);
        $file = $request->files->get("image");
        $data = $this->get('user.user')->uploadPictureFile($hash, $file);
        return $helpers->json($data);
    }
}