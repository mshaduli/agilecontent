<?php

namespace TNE\OperatorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PaymentDetails
 */
class PaymentDetails
{
    /**
     * @var string
     */
    private $name_on_card;

    /**
     * @var string
     */
    private $card_type;

    /**
     * @var string
     */
    private $card_number;

    /**
     * @var string
     */
    private $ccv;

    /**
     * @var string
     */
    private $expiry_month;

    /**
     * @var string
     */
    private $expiry_year;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \TNE\OperatorBundle\Entity\Customer
     */
    private $customer;


    /**
     * Set name_on_card
     *
     * @param string $nameOnCard
     * @return PaymentDetails
     */
    public function setNameOnCard($nameOnCard)
    {
        $this->name_on_card = $nameOnCard;
    
        return $this;
    }

    /**
     * Get name_on_card
     *
     * @return string 
     */
    public function getNameOnCard()
    {
        return $this->name_on_card;
    }

    /**
     * Set card_type
     *
     * @param string $cardType
     * @return PaymentDetails
     */
    public function setCardType($cardType)
    {
        $this->card_type = $cardType;
    
        return $this;
    }

    /**
     * Get card_type
     *
     * @return string 
     */
    public function getCardType()
    {
        return $this->card_type;
    }

    /**
     * Set card_number
     *
     * @param string $cardNumber
     * @return PaymentDetails
     */
    public function setCardNumber($cardNumber)
    {
        $this->card_number = $cardNumber;
    
        return $this;
    }

    /**
     * Get card_number
     *
     * @return string 
     */
    public function getCardNumber()
    {
        return $this->card_number;
    }

    /**
     * Set ccv
     *
     * @param string $ccv
     * @return PaymentDetails
     */
    public function setCcv($ccv)
    {
        $this->ccv = $ccv;
    
        return $this;
    }

    /**
     * Get ccv
     *
     * @return string 
     */
    public function getCcv()
    {
        return $this->ccv;
    }

    /**
     * Set expiry_month
     *
     * @param string $expiryMonth
     * @return PaymentDetails
     */
    public function setExpiryMonth($expiryMonth)
    {
        $this->expiry_month = $expiryMonth;
    
        return $this;
    }

    /**
     * Get expiry_month
     *
     * @return string 
     */
    public function getExpiryMonth()
    {
        return $this->expiry_month;
    }

    /**
     * Set expiry_year
     *
     * @param string $expiryYear
     * @return PaymentDetails
     */
    public function setExpiryYear($expiryYear)
    {
        $this->expiry_year = $expiryYear;
    
        return $this;
    }

    /**
     * Get expiry_year
     *
     * @return string 
     */
    public function getExpiryYear()
    {
        return $this->expiry_year;
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
     * Set customer
     *
     * @param \TNE\OperatorBundle\Entity\Customer $customer
     * @return PaymentDetails
     */
    public function setCustomer(\TNE\OperatorBundle\Entity\Customer $customer = null)
    {
        $this->customer = $customer;
    
        return $this;
    }

    /**
     * Get customer
     *
     * @return \TNE\OperatorBundle\Entity\Customer 
     */
    public function getCustomer()
    {
        return $this->customer;
    }
}