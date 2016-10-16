<?php

namespace BackendBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/usergroup")
 */
class UsergroupController extends Controller
{
    /**
     * creates a group
     *
     * @Route("/new", name="new_usergroup")
     * @Method("POST")
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function newAction(Request $request)
    {
        $helpers = $this->get("app.helpers");
        $hash = $request->headers->get('authorization', null);
        $json = $request->get("json", null);
        $data = $this->get('usergroup.usergroup')->createUsergroup(
            $hash,
            $json
        );
        return $helpers->json($data);
    }

    /**
     * edits a group
     *
     * @Route("/edit", name="edit_usergroup")
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
        $data = $this->get('usergroup.usergroup')->editUsergroup(
            $hash,
            $json
        );
        return $helpers->json($data);
    }
}