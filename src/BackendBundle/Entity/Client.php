<?php

namespace BackendBundle\Entity;

/**
 * Client
 */
class Client
{
    /**
     * @var string
     */
    private $clientNumber;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $place;

    /**
     * @var integer
     */
    private $phone;

    /**
     * @var string
     */
    private $fax;

    /**
     * @var string
     */
    private $web;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $others;

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
     * @var \BackendBundle\Entity\Clienttype
     */
    private $clienttype;

    /**
     * @var \BackendBundle\Entity\Institution
     */
    private $institution;

    /**
     * @var \BackendBundle\Entity\User
     */
    private $user;

    /**
     * @var \BackendBundle\Entity\Usergroup
     */
    private $usergroup;


    /**
     * Set clientNumber
     *
     * @param string $clientNumber
     *
     * @return Client
     */
    public function setClientNumber($clientNumber)
    {
        $this->clientNumber = $clientNumber;

        return $this;
    }

    /**
     * Get clientNumber
     *
     * @return string
     */
    public function getClientNumber()
    {
        return $this->clientNumber;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Client
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set place
     *
     * @param string $place
     *
     * @return Client
     */
    public function setPlace($place)
    {
        $this->place = $place;

        return $this;
    }

    /**
     * Get place
     *
     * @return string
     */
    public function getPlace()
    {
        return $this->place;
    }

    /**
     * Set phone
     *
     * @param integer $phone
     *
     * @return Client
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return integer
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set fax
     *
     * @param string $fax
     *
     * @return Client
     */
    public function setFax($fax)
    {
        $this->fax = $fax;

        return $this;
    }

    /**
     * Get fax
     *
     * @return string
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * Set web
     *
     * @param string $web
     *
     * @return Client
     */
    public function setWeb($web)
    {
        $this->web = $web;

        return $this;
    }

    /**
     * Get web
     *
     * @return string
     */
    public function getWeb()
    {
        return $this->web;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Client
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set others
     *
     * @param string $others
     *
     * @return Client
     */
    public function setOthers($others)
    {
        $this->others = $others;

        return $this;
    }

    /**
     * Get others
     *
     * @return string
     */
    public function getOthers()
    {
        return $this->others;
    }

    /**
     * Set dateValidFrom
     *
     * @param \DateTime $dateValidFrom
     *
     * @return Client
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
     * @return Client
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
     * @return Client
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
     * Set clienttype
     *
     * @param \BackendBundle\Entity\Clienttype $clienttype
     *
     * @return Client
     */
    public function setClienttype(\BackendBundle\Entity\Clienttype $clienttype = null)
    {
        $this->clienttype = $clienttype;

        return $this;
    }

    /**
     * Get clienttype
     *
     * @return \BackendBundle\Entity\Clienttype
     */
    public function getClienttype()
    {
        return $this->clienttype;
    }

    /**
     * Set institution
     *
     * @param \BackendBundle\Entity\Institution $institution
     *
     * @return Client
     */
    public function setInstitution(\BackendBundle\Entity\Institution $institution = null)
    {
        $this->institution = $institution;

        return $this;
    }

    /**
     * Get institution
     *
     * @return \BackendBundle\Entity\Institution
     */
    public function getInstitution()
    {
        return $this->institution;
    }

    /**
     * Set user
     *
     * @param \BackendBundle\Entity\User $user
     *
     * @return Client
     */
    public function setUser(\BackendBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \BackendBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set usergroup
     *
     * @param \BackendBundle\Entity\Usergroup $usergroup
     *
     * @return Client
     */
    public function setUsergroup(\BackendBundle\Entity\Usergroup $usergroup = null)
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
