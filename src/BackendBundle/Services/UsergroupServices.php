<?php

namespace BackendBundle\Services;


use AppBundle\Services\Helpers;
use AppBundle\Services\JwtAuth;
use BackendBundle\Entity\User;
use BackendBundle\Entity\Usergroup;
use Doctrine\ORM\EntityManager;

class UsergroupServices
{
    protected $em;
    protected $userRep;
    protected $helpers;
    protected $jwtService;

    public function __construct(
        EntityManager $entityManager,
        Helpers $helpers,
        JwtAuth $jwtAuth
    ) {
        $this->em = $entityManager;
        $this->userRep = $entityManager->getRepository("BackendBundle:User");
        $this->helpers = $helpers;
        $this->jwtService = $jwtAuth;
    }

    /**
     * Creates a new usergroup. For this, the function check if the user is logged. If it's logged, it creates the
     * group and set the current user as owner (owner = 1)
     *
     * @param string $hash
     * @param string $json
     *
     * @return array with the status, the message and the code.
     * If the usergroup is not valid, it returns Symfony's message validation
     */
    public function createUsergroup($hash, $json)
    {
        $authCheck = $this->helpers->authCheck($hash);

        if ($authCheck == true) {
            $identity = $this->helpers->authCheck($hash, true);

            $user = $this->userRep->findOneBy(array(
                "id" => $identity->sub
            ));

            $usergroupFromUser = $user->getUsergroup();
            if ($usergroupFromUser === null || empty($usergroupFromUser)) {

                if ($json != null) {

                    $params = json_decode($json);
                    $createdAt = new \Datetime("now");

                    $name = (isset($params->name)) ? $params->name : null;

                    $usergroup = new Usergroup();
                    $usergroup->setDateLastEdited($createdAt);
                    $usergroup->setDateValidFrom($createdAt);
                    $usergroup->setName($name);

                    $user->setUsergroup($usergroup);
                    $user->setOwner(1);


                    $errorMsg = $this->helpers->validateWrongDataInEntity($usergroup);

                    if (!$errorMsg) {
                        $this->em->persist($usergroup);
                        $this->em->persist($user);
                        $this->em->flush();
                        $data = array(
                            "status" => "success",
                            "code" => 200,
                            "msg" => "New group created"
                        );
                    } else {
                        $data = array(
                            "status" => "error",
                            "code" => 400,
                            "msg" => $errorMsg
                        );
                    }
                } else {
                    $data = array(
                        "status" => "error",
                        "code" => 400,
                        "msg" => "No parameters sent"
                    );
                }
            } else {
                $data = array(
                    "status" => "error",
                    "code" => 409,
                    "msg" => "You have already a group assigned"
                );
            }
        } else{
            $data = array(
                "status" => "error",
                "code"	 => 401,
                "msg"	 => "Authorization not valid"
            );
        }
        return $data;
    }

    /**
     * Edit an existent usergroup
     *
     * @param string $hash
     * @param string $json
     *
     * @return array with the status, the message and the code.
     * If the usergroup is not valid, it returns Symfony's message validation
     */
    public function editUsergroup($hash, $json)
    {
        $authCheck = $this->helpers->authCheck($hash);

        if ($authCheck == true) {
            $identity = $this->helpers->authCheck($hash, true);

            $user = $this->userRep->findOneBy(array(
                "id" => $identity->sub
            ));

            $usergroupFromUser = $user->getUsergroup();
            if ($usergroupFromUser !== null && !empty($usergroupFromUser)) {

                if ($json != null) {

                    $params = json_decode($json);
                    $createdAt = new \Datetime("now");

                    $name = (isset($params->name)) ? $params->name : null;

                    $usergroupFromUser->setDateLastEdited($createdAt);
                    $usergroupFromUser->setName($name);

                    $errorMsg = $this->helpers->validateWrongDataInEntity($usergroupFromUser);

                    if (!$errorMsg) {
                        $this->em->persist($usergroupFromUser);
                        $this->em->flush();
                        $data = array(
                            "status" => "success",
                            "code" => 200,
                            "msg" => "Usergroup edited"
                        );
                    } else {
                        $data = array(
                            "status" => "error",
                            "code" => 400,
                            "msg" => $errorMsg
                        );
                    }
                } else {
                    $data = array(
                        "status" => "error",
                        "code" => 400,
                        "msg" => "No parameters sent"
                    );
                }
            } else {
                $data = array(
                    "status" => "error",
                    "code" => 404,
                    "msg" => "You don't have any group assigned yet"
                );
            }
        } else{
            $data = array(
                "status" => "error",
                "code"	 => 401,
                "msg"	 => "Authorization not valid"
            );
        }
        return $data;
    }
}