<?php

namespace BackendBundle\Entity;

/**
 * Usergroup
 */
class Usergroup
{
    /**
     * @var string
     */
    private $name;

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
     * @var \BackendBundle\Entity\User
     */
    private $user;


    /**
     * Set name
     *
     * @param string $name
     *
     * @return Usergroup
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
     * Set dateValidFrom
     *
     * @param \DateTime $dateValidFrom
     *
     * @return Usergroup
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
     * @return Usergroup
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
     * @return Usergroup
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
     * Set id
     *
     * @param integer $id
     *
     * @return Usergroup
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
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
     * Set user
     *
     * @param \BackendBundle\Entity\User $user
     *
     * @return Usergroup
     */
    public function setUser(\BackendBundle\Entity\User $user)
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
}
