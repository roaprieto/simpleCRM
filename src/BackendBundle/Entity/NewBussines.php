<?php

namespace BackendBundle\Entity;

/**
 * NewBussines
 */
class NewBussines
{
    /**
     * @var string
     */
    private $product;

    /**
     * @var integer
     */
    private $interest;

    /**
     * @var integer
     */
    private $turnover;

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
     * @return NewBussines
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
     * Set interest
     *
     * @param integer $interest
     *
     * @return NewBussines
     */
    public function setInterest($interest)
    {
        $this->interest = $interest;

        return $this;
    }

    /**
     * Get interest
     *
     * @return integer
     */
    public function getInterest()
    {
        return $this->interest;
    }

    /**
     * Set turnover
     *
     * @param integer $turnover
     *
     * @return NewBussines
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
     * Set dateValidFrom
     *
     * @param \DateTime $dateValidFrom
     *
     * @return NewBussines
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
     * @return NewBussines
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
     * @return NewBussines
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
     * @return NewBussines
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
     * @return NewBussines
     */
    public function setClientsContact(\BackendBundle\Entity\ClientsContact $clientsContact)
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
