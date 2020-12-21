<?php

namespace App\Entity;

use App\Entity\Traits\IdTrait;
use CommerceGuys\Addressing\AddressInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AddressRepository")
 */
class Address implements AddressInterface

{
    use IdTrait;
    /**
     * Gets the two-letter country code.
     *
     * @return string
     */
    private $countryCode;

    /**
     * Administration area
     *
     * @var string
     */
    private $administrativeArea;

    /**
     * the locality (i.e city).
     *
     * @var string
     */
    private $locality;

    /**
     * the dependent locality (i.e neighbourhood).
     *
     * @var string
     */
    private $dependentLocality;


    /**
     *  the postal code.
     *
     * @var string
     */
    private $postalCode;


    /**
     *   the sorting code.
     *
     * @var string
     */
    private $sortingCode;


    /**
     *   the first line of address 1 block.
     *
     * @var string
     */
    private $addressLine1;

    /**
     *   the first line of address 2 block.
     *
     * @var string
     */
    private $addressLine2;


    /**
     *   the first line of address 2 block.
     *
     * @var string
     */
    private $organization;
    

     /**
     * the given name (i.e first name).
     *
     * @var string
     */
    private $givenName;
    

    /**
     * the additional name.
     *
     * @var string
     */
    private $additionalName;

    /**
     * the family name (i.e last name).
     *
     * @var string
     */
    private $familyName;

     /**
     * the locale
     *
     * @var string
     */
    private $locale;
    
     

    /**
     * Get gets the two-letter country code.
     */ 
    public function getCountryCode()
    {
        return $this->countryCode;
    }

    /**
     * Set gets the two-letter country code.
     *
     * @return  self
     */ 
    public function setCountryCode($countryCode)
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    /**
     * Get administration area
     *
     * @return  string
     */ 
    public function getAdministrativeArea()
    {
        return $this->administrativeArea;
    }

    /**
     * Set administration area
     *
     * @param  string  $administrativeArea  Administration area
     *
     * @return  self
     */ 
    public function setAdministrativeArea(string $administrativeArea)
    {
        $this->administrativeArea = $administrativeArea;

        return $this;
    }

    /**
     * Get the locality (i.e city).
     *
     * @return  string
     */ 
    public function getLocality()
    {
        return $this->locality;
    }

    /**
     * Set the locality (i.e city).
     *
     * @param  string  $locality  the locality (i.e city).
     *
     * @return  self
     */ 
    public function setLocality(string $locality)
    {
        $this->locality = $locality;

        return $this;
    }

    /**
     * Get the dependent locality (i.e neighbourhood).
     *
     * @return  string
     */ 
    public function getDependentLocality()
    {
        return $this->dependentLocality;
    }

    /**
     * Set the dependent locality (i.e neighbourhood).
     *
     * @param  string  $dependentLocality  the dependent locality (i.e neighbourhood).
     *
     * @return  self
     */ 
    public function setDependentLocality(string $dependentLocality)
    {
        $this->dependentLocality = $dependentLocality;

        return $this;
    }

    /**
     * Get the postal code.
     *
     * @return  string
     */ 
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * Set the postal code.
     *
     * @param  string  $postalCode  the postal code.
     *
     * @return  self
     */ 
    public function setPostalCode(string $postalCode)
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    /**
     * Get the sorting code.
     *
     * @return  string
     */ 
    public function getSortingCode()
    {
        return $this->sortingCode;
    }

    /**
     * Set the sorting code.
     *
     * @param  string  $sortingCode  the sorting code.
     *
     * @return  self
     */ 
    public function setSortingCode(string $sortingCode)
    {
        $this->sortingCode = $sortingCode;

        return $this;
    }

    /**
     * Get the first line of address 1 block.
     *
     * @return  string
     */ 
    public function getAddressLine1()
    {
        return $this->addressLine1;
    }

    /**
     * Set the first line of address 1 block.
     *
     * @param  string  $addressLine1  the first line of address 1 block.
     *
     * @return  self
     */ 
    public function setAddressLine1(string $addressLine1)
    {
        $this->addressLine1 = $addressLine1;

        return $this;
    }

    /**
     * Get the first line of address 2 block.
     *
     * @return  string
     */ 
    public function getAddressLine2()
    {
        return $this->addressLine2;
    }

    /**
     * Set the first line of address 2 block.
     *
     * @param  string  $addressLine2  the first line of address 2 block.
     *
     * @return  self
     */ 
    public function setAddressLine2(string $addressLine2)
    {
        $this->addressLine2 = $addressLine2;

        return $this;
    }

    /**
     * Get the first line of address 2 block.
     *
     * @return  string
     */ 
    public function getOrganization()
    {
        return $this->organization;
    }

    /**
     * Set the first line of address 2 block.
     *
     * @param  string  $organization  the first line of address 2 block.
     *
     * @return  self
     */ 
    public function setOrganization(string $organization)
    {
        $this->organization = $organization;

        return $this;
    }

    /**
     * Get the given name (i.e first name).
     *
     * @return  string
     */ 
    public function getGivenName()
    {
        return $this->givenName;
    }

    /**
     * Set the given name (i.e first name).
     *
     * @param  string  $givenName  the given name (i.e first name).
     *
     * @return  self
     */ 
    public function setGivenName(string $givenName)
    {
        $this->givenName = $givenName;

        return $this;
    }

    /**
     * Get the additional name.
     *
     * @return  string
     */ 
    public function getAdditionalName()
    {
        return $this->additionalName;
    }

    /**
     * Set the additional name.
     *
     * @param  string  $additionalName  the additional name.
     *
     * @return  self
     */ 
    public function setAdditionalName(string $additionalName)
    {
        $this->additionalName = $additionalName;

        return $this;
    }

    /**
     * Get the family name (i.e last name).
     *
     * @return  string
     */ 
    public function getFamilyName()
    {
        return $this->familyName;
    }

    /**
     * Set the family name (i.e last name).
     *
     * @param  string  $familyName  the family name (i.e last name).
     *
     * @return  self
     */ 
    public function setFamilyName(string $familyName)
    {
        $this->familyName = $familyName;

        return $this;
    }

    /**
     * Get the locale
     *
     * @return  string
     */ 
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * Set the locale
     *
     * @param  string  $locale  the locale
     *
     * @return  self
     */ 
    public function setLocale(string $locale)
    {
        $this->locale = $locale;

        return $this;
    }
}

