<?php
/**
 * Created by PhpStorm.
 * User: francisco
 * Date: 10.10.16
 * Time: 19:46
 */

namespace BackendBundle\Services;


use AppBundle\Services\Helpers;
use AppBundle\Services\JwtAuth;
use BackendBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UserServices
{
    protected $em;
    protected $userRep;
    protected $helpers;
    protected $jwtService;
    protected $profileImg;

    public function __construct(
        EntityManager $entityManager,
        Helpers $helpers,
        JwtAuth $jwtAuth,
        $profileImg
    ) {
        $this->em = $entityManager;
        $this->userRep = $entityManager->getRepository("BackendBundle:User");
        $this->helpers = $helpers;
        $this->jwtService = $jwtAuth;
        $this->setProfileImg($profileImg);
    }

    /**
     * Set the default profile picture.
     *
     * @param $profileImg
     *
     * @return void
     */
    public function setProfileImg($profileImg)
    {
        $this->profileImg = $profileImg;
    }

    /**
     * Get the default profile picture.
     *
     * @return $this
     */
    public function getProfileImg()
    {
        return $this->profileImg;
    }

    /**
     * Add new user.
     *
     * @param string $json params to change the user entity
     *
     * @return array with the status, the message and the code.
     * If the usergroup is not valid, it returns Symfony's message validation
     */
    public function addNewUser($json)
    {
        if ($json != null) {
            $params = json_decode($json);
            $createdAt = new \Datetime("now");

            $email = (isset($params->email)) ? $params->email : null;
            $username = (isset($params->username) && ctype_alpha($params->username)) ? $params->username : null;
            $password = (isset($params->password)) ? $params->password : null;


            $user = new User();
            $user->setDateLastEdited($createdAt);
            $user->setDateValidFrom($createdAt);
            $user->setEmail($email);
            $user->setUsername($username);
            $user->setProfilePicture($this->profileImg);
            if($password == null || empty($password)){
                $user->setPassword(null);
            } else {
                $pwd = hash('sha512', $password);
                $user->setPassword($pwd);
            }

            $errorMsg = $this->helpers->validateWrongDataInEntity($user);

            if (!$errorMsg) {
                $arrTestRepeated = array('email', 'username');
                $repeatedErrorMsg = $this->helpers->validateRepeatedDataInEntity($user, $arrTestRepeated);

                if (!$repeatedErrorMsg) {
                    $this->em->persist($user);
                    $this->em->flush();

                    $data = array(
                        "status" => "success",
                        "code" => 200,
                        "msg" => "New user created"
                    );
                } else {
                    $data = array(
                        "status" => "error",
                        "code" => 409,
                        "msg" => $repeatedErrorMsg
                    );
                }
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
        return $data;
    }

    /**
     * Edit user.
     *
     * @param string $hash user's hash
     * @param string $json params to change the user entity
     *
     * @return array with the status, the message and the code.
     * If the usergroup is not valid, it returns Symfony's message validation
     */
    public function editUserFromIdentityWithParams($hash, $json)
    {
        $authCheck = $this->helpers->authCheck($hash);

        if ($authCheck == true) {
            $identity = $this->helpers->authCheck($hash, true);

            $user = $this->userRep->findOneBy(array(
                "id" => $identity->sub
            ));

            $params = json_decode($json);

            $data = array(
                "status" => "error",
                "code" => 400,
                "msg" => "User not updated"
            );

            if ($json != null) {
                $createdAt = new \Datetime("now");
                $image = null;

                $email = (isset($params->email)) ? $params->email : null;
                $username = (isset($params->username) && ctype_alpha($params->username)) ? $params->username : null;
                $password = (isset($params->password)) ? $params->password : null;

                $user->setDateLastEdited($createdAt);
                $user->setEmail($email);
                $user->setUsername($username);
                if($password == null || empty($password)){
                    $user->setPassword(null);
                } else {
                    $pwd = hash('sha512', $password);
                    $user->setPassword($pwd);
                }

                $errorMsg = $this->helpers->validateWrongDataInEntity($user);

                if (!$errorMsg) {
                    $arrTestRepeated = array('email', 'username');
                    $repeatedErrorMsg = $this->helpers->validateRepeatedDataInEntity($user, $arrTestRepeated);

                    if (!$repeatedErrorMsg) {
                        $this->em->persist($user);
                        $this->em->flush();

                        $data["status"] = 'success';
                        $data["code"] = 200;
                        $data["msg"] = 'User updated';
                    } else {
                        $data = array(
                            "status" => "error",
                            "code" => 409,
                            "msg" => "User not updated, duplicated"
                        );
                    }
                } else {
                    $data = array(
                        "status" => "error",
                        "code" => 400,
                        "msg" => "Parameters not valid"
                    );
                }

            } else {
                $data = array(
                    "status" => "error",
                    "code" => 400,
                    "msg" => "No json parameter found"
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
     * Upload picture file
     *
     * @param string                                               $hash user's hash
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile  $file file to be uploaded
     *
     * @return array with the status, the message and the code.
     * If the usergroup is not valid, it returns Symfony's message validation
     */
    public function uploadPictureFile($hash, UploadedFile $file)
    {
        $authCheck = $this->helpers->authCheck($hash);

        if($authCheck){
            $identity = $this->helpers->authCheck($hash, true);

            $user = $this->userRep->findOneBy(array(
                "id" => $identity->sub
            ));

            if(!empty($file) && $file != null){
                $ext = $file->guessExtension();

                if($ext == "jpeg" || $ext == "jpg" ||
                    $ext == "png" || $ext == "gif"){

                    $fileName = $user->getId().".".$ext;
                    $file->move("uploads/users", $fileName);

                    $user->setProfilePicture($fileName);
                    $user->setDateLastEdited(new \DateTime('now'));
                    $this->em->persist($user);
                    $this->em->flush();

                    $data = array(
                        "status" => "success",
                        "code"	 => 200,
                        "msg"	 => "Image for user uploaded success"
                    );
                }else{
                    $data = array(
                        "status" => "error",
                        "code"	 => 400,
                        "msg"	 => "File not valid"
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
                "code"	 => 401,
                "msg"	 => "Authorization not valid"
            );
        }
        return $data;
    }
}