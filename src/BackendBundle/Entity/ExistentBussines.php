<?php

namespace BackendBundle\Entity;

/**
 * ExistentBussines
 */
class ExistentBussines
{
    /**
     * @var string
     */
    private $product;

    /**
     * @var integer
     */
    private $potential;

    /**
     * @var integer
     */
    private $turnover;

    /**
     * @var integer
     */
    private $turnoverPerYear;

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
     * Set product
     *
     * @param string $product
     *
     * @return ExistentBussines
     */
    public function setProduct($product)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return string
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set potential
     *
     * @param integer $potential
     *
     * @return ExistentBussines
     */
    public function setPotential($potential)
    {
        $this->potential = $potential;

        return $this;
    }

    /**
     * Get potential
     *
     * @return integer
     */
    public function getPotential()
    {
        return $this->potential;
    }

    /**
     * Set turnover
     *
     * @param integer $turnover
     *
     * @return ExistentBussines
     */
    public function setTurnover($turnover)
    {
        $this->turnover = $turnover;

        return $this;
    }

    /**
     * Get turnover
     *
     * @return integer
     */
    public function getTurnover()
    {
        return $this->turnover;
    }

    /**
     * Set turnoverPerYear
     *
     * @param integer $turnoverPerYear
     *
     * @return ExistentBussines
     */
    public function setTurnoverPerYear($turnoverPerYear)
    {
        $this->turnoverPerYear = $turnoverPerYear;

        return $this;
    }

    /**
     * Get turnoverPerYear
     *
     * @return integer
     */
    public function getTurnoverPerYear()
    {
        return $this->turnoverPerYear;
    }

    /**
     * Set dateValidFrom
     *
     * @param \DateTime $dateValidFrom
     *
     * @return ExistentBussines
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
     * @return ExistentBussines
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
     * @return ExistentBussines
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
     * @return ExistentBussines
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
     * Set clientsContact
     *
     * @param \BackendBundle\Entity\ClientsContact $clientsContact
     *
     * @return ExistentBussines
     */
    public function setClientsContact(ClientsContact $clientsContact)
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
}

