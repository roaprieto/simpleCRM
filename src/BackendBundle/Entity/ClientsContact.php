<?php

namespace BackendBundle\Entity;

/**
 * ClientsContact
 */
class ClientsContact
{
    /**
     * @var string
     */
    private $genre;

    /**
     * @var string
     */
    private $academicTitle;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $lastname;

    /**
     * @var string
     */
    private $position;

    /**
     * @var string
     */
    private $department;

    /**
     * @var string
     */
    private $address;

    /**
     * @var integer
     */
    private $phone;

    /**
     * @var integer
     */
    private $fax;

    /**
     * @var string
     */
    private $web;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $emailTwo;

    /**
     * @var string
     */
    private $mobile;

    /**
     * @var string
     */
    private $foto;

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
     * @var \BackendBundle\Entity\ClientsContact
     */
    private $clientsContact;

    /**
     * @var \BackendBundle\Entity\User
     */
    private $user;

    /**
     * @var \BackendBundle\Entity\Client
     */
    private $client;


    /**
     * Set genre
     *
     * @param string $genre
     *
     * @return ClientsContact
     */
    public function setGenre($genre)
    {
        $this->genre = $genre;

        return $this;
    }

    /**
     * Get genre
     *
     * @return string
     */
    public function getGenre()
    {
        return $this->genre;
    }

    /**
     * Set academicTitle
     *
     * @param string $academicTitle
     *
     * @return ClientsContact
     */
    public function setAcademicTitle($academicTitle)
    {
        $this->academicTitle = $academicTitle;

        return $this;
    }

    /**
     * Get academicTitle
     *
     * @return string
     */
    public function getAcademicTitle()
    {
        return $this->academicTitle;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return ClientsContact
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
     * Set lastname
     *
     * @param string $lastname
     *
     * @return ClientsContact
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set position
     *
     * @param string $position
     *
     * @return ClientsContact
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return string
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set department
     *
     * @param string $department
     *
     * @return ClientsContact
     */
    public function setDepartment($department)
    {
        $this->department = $department;

        return $this;
    }

    /**
     * Get department
     *
     * @return string
     */
    public function getDepartment()
    {
        return $this->department;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return ClientsContact
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set phone
     *
     * @param integer $phone
     *
     * @return ClientsContact
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
     * @param integer $fax
     *
     * @return ClientsContact
     */
    public function setFax($fax)
    {
        $this->fax = $fax;

        return $this;
    }

    /**
     * Get fax
     *
     * @return integer
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
     * @return ClientsContact
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
     * Set email
     *
     * @param string $email
     *
     * @return ClientsContact
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
     * Set emailTwo
     *
     * @param string $emailTwo
     *
     * @return ClientsContact
     */
    public function setEmailTwo($emailTwo)
    {
        $this->emailTwo = $emailTwo;

        return $this;
    }

    /**
     * Get emailTwo
     *
     * @return string
     */
    public function getEmailTwo()
    {
        return $this->emailTwo;
    }

    /**
     * Set mobile
     *
     * @param string $mobile
     *
     * @return ClientsContact
     */
    public function setMobile($mobile)
    {
        $this->mobile = $mobile;

        return $this;
    }

    /**
     * Get mobile
     *
     * @return string
     */
    public function getMobile()
    {
        return $this->mobile;
    }

    /**
     * Set foto
     *
     * @param string $foto
     *
     * @return ClientsContact
     */
    public function setFoto($foto)
    {
        $this->foto = $foto;

        return $this;
    }

    /**
     * Get foto
     *
     * @return string
     */
    public function getFoto()
    {
        return $this->foto;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return ClientsContact
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
     * @return ClientsContact
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
     * @return ClientsContact
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
     * @return ClientsContact
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
     * @return ClientsContact
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
     * Set clientsContact
     *
     * @param \BackendBundle\Entity\ClientsContact $clientsContact
     *
     * @return ClientsContact
     */
    public function setClientsContact(\BackendBundle\Entity\ClientsContact $clientsContact = null)
    {
        $this->clientsContact = $clientsContact;

        return $this;
    }

    /**
     * Get clientsContact
     *
     * @return \BackendBundle\Entity\ClientsContact
     */
    public function getClientsContact()
    {
        return $this->clientsContact;
    }

    /**
     * Set user
     *
     * @param \BackendBundle\Entity\User $user
     *
     * @return ClientsContact
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
     * Set client
     *
     * @param \BackendBundle\Entity\Client $client
     *
     * @return ClientsContact
     */
    public function setClient(\BackendBundle\Entity\Client $client = null)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get client
     *
     * @return \BackendBundle\Entity\Client
     */
    public function getClient()
    {
        return $this->client;
    }
}
