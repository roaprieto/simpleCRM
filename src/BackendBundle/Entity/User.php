<?php

namespace BackendBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 */
class User
{
    /**
     * @var string
     */
    private $owner = '0';

    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=3)
     * @var string
     */
    private $username;

    /**
     * @Assert\NotBlank()
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email.",
     *     checkMX = true
     * )
     * @var string
     */
    private $email;

    /**
     * @Assert\NotBlank()
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $profilePicture;

    /**
     * @var \DateTime
     */
    private $dateValidFrom;

    /**
     * @var \DateTime
     */
    private $dateValidTo;

    /**
     * @var \DateTime
     */
    private $dateLastEdited;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \BackendBundle\Entity\Usergroup
     */
    private $usergroup;


    /**
     * Set owner
     *
     * @param string $owner
     *
     * @return User
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get owner
     *
     * @return string
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Set username
     *
     * @param string $username
     *
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set profilePicture
     *
     * @param string $profilePicture
     *
     * @return User
     */
    public function setProfilePicture($profilePicture)
    {
        $this->profilePicture = $profilePicture;

        return $this;
    }

    /**
     * Get profilePicture
     *
     * @return string
     */
    public function getProfilePicture()
    {
        return $this->profilePicture;
    }

    /**
     * Set dateValidFrom
     *
     * @param \DateTime $dateValidFrom
     *
     * @return User
     */
    public function setDateValidFrom($dateValidFrom)
    {
        $this->dateValidFrom = $dateValidFrom;

        return $this;
    }

    /**
     * Get dateValidFrom
     *
     * @return \DateTime
     */
    public function getDateValidFrom()
    {
        return $this->dateValidFrom;
    }

    /**
     * Set dateValidTo
     *
     * @param \DateTime $dateValidTo
     *
     * @return User
     */
    public function setDateValidTo($dateValidTo)
    {
        $this->dateValidTo = $dateValidTo;

        return $this;
    }

    /**
     * Get dateValidTo
     *
     * @return \DateTime
     */
    public function getDateValidTo()
    {
        return $this->dateValidTo;
    }

    /**
     * Set dateLastEdited
     *
     * @param \DateTime $dateLastEdited
     *
     * @return User
     */
    public function setDateLastEdited($dateLastEdited)
    {
        $this->dateLastEdited = $dateLastEdited;

        return $this;
    }

    /**
     * Get dateLastEdited
     *
     * @return \DateTime
     */
    public function getDateLastEdited()
    {
        return $this->dateLastEdited;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set usergroup
     *
     * @param \BackendBundle\Entity\Usergroup $usergroup
     *
     * @return User
     */
    public function setUsergroup(Usergroup $usergroup = null)
    {
        $this->usergroup = $usergroup;

        return $this;
    }

    /**
     * Get usergroup
     *
     * @return \BackendBundle\Entity\Usergroup
     */
    public function getUsergroup()
    {
        return $this->usergroup;
    }
}

