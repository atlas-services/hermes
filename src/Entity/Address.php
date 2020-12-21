<?php

namespace App\Entity;

use CommerceGuys\Addressing\AddressInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AddressRepository")
 */
class Address implements AddressInterface

{
    /**
     * Gets the two-letter country code.
     *
     * @return string
     */
    private $_countryCode;

    /**
     * Administration area
     *
     * @var string
     */
    private $_administrativeArea;

    /**
     * the locality (i.e city).
     *
     * @var string
     */
    private $_locality;

    /**
     * the dependent locality (i.e neighbourhood).
     *
     * @var string
     */
    private $_dependentLocality;


    /**
     *  the postal code.
     *
     * @var string
     */
    private $_postalCode;


    /**
     *   the sorting code.
     *
     * @var string
     */
    private $_sortingCode;


    /**
     *   the first line of address 1 block.
     *
     * @var string
     */
    private $_addressLine1;

    /**
     *   the first line of address 2 block.
     *
     * @var string
     */
    private $_addressLine2;


    /**
     *   the first line of address 2 block.
     *
     * @var string
     */
    private $_organization;
    

     /**
     * the given name (i.e first name).
     *
     * @var string
     */
    private $_givenName;
    

    /**
     * the additional name.
     *
     * @var string
     */
    private $_additionalName;

    /**
     * the family name (i.e last name).
     *
     * @var string
     */
    private $_familyName;

     /**
     * the locale
     *
     * @var string
     */
    private $_locale;
    
    

    /**
     * Gets the two-letter country code.
     *
     * This is a CLDR country code, since CLDR includes additional countries
     * for addressing purposes, such as Canary Islands (IC).
     *
     * @return string The two-letter country code.
     */
    public function getCountryCode() 
    {

    }

    /**
     * Gets the administrative area.
     *
     * Called the "state" in the United States, "province" in France and Italy,
     * "county" in Great Britain, "prefecture" in Japan, etc.
     *
     * @return string The administrative area. A subdivision code if there
     *                are predefined subdivision at this level.
     */
    public function getAdministrativeArea()
    {

    }

    /**
     * Gets the locality (i.e city).
     *
     * Some countries do not use this field; their address lines are sufficient
     * to locate an address within a sub-administrative area.
     *
     * @return string The administrative area. A subdivision code if there
     *                are predefined subdivision at this level.
     */
    public function getLocality()
    {

    }

    /**
     * Gets the dependent locality (i.e neighbourhood).
     *
     * When representing a double-dependent locality in Great Britain, includes
     * both the double-dependent locality and the dependent locality,
     * e.g. "Whaley, Langwith".
     *
     * @return string The administrative area. A subdivision code if there
     *                are predefined subdivision at this level.
     */
    public function getDependentLocality()
    {

    }

    /**
     * Gets the postal code.
     *
     * The value is often alphanumeric.
     *
     * @return string The postal code.
     */
    public function getPostalCode()
    {

    }

    /**
     * Gets the sorting code.
     *
     * For example, CEDEX in France.
     *
     * @return string The sorting code.
     */
    public function getSortingCode()
    {

    }

    /**
     * Gets the first line of address block.
     *
     * @return string The first line of the address block.
     */
    public function getAddressLine1()
    {

    }

    /**
     * Gets the second line of address block.
     *
     * @return string The second line of the address block.
     */
    public function getAddressLine2()
    {

    }

    /**
     * Gets the organization.
     *
     * @return string The organization.
     */
    public function getOrganization()
    {

    }

    /**
     * Gets the given name (i.e first name).
     *
     * @return string The given name.
     */
    public function getGivenName()
    {

    }

    /**
     * Gets the additional name.
     *
     * Can be used to hold a middle name, or a patronymic.
     * If a remote API does not have an additional_name/middle_name parameter,
     * append it to the given name.
     *
     * @return string The additional name.
     */
    public function getAdditionalName()
    {

    }

    /**
     * Gets the family name (i.e last name).
     *
     * @return string The family name.
     */
    public function getFamilyName()
    {

    }

    /**
     * Gets the locale.
     *
     * Allows the initially-selected address format / subdivision translations
     * to be selected and used the next time this address is modified.
     *
     * @return string The locale.
     */
    public function getLocale()
    {

    }

}

