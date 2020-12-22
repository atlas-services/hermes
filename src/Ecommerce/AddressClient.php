<?php

namespace App\Ecommerce;

use CommerceGuys\Addressing\AddressFormat\AddressFormatRepository;
use CommerceGuys\Addressing\Country\CountryRepository;
use CommerceGuys\Addressing\Subdivision\SubdivisionRepository;

class AddressClient
{
    private $countryRepository;
    private $addressFormatRepository;
    private $subdivisionRepository;
    public function __construct(CountryRepository $countryRepository, AddressFormatRepository $addressFormatRepository, SubdivisionRepository $subdivisionRepository)
    {
        $this->countryRepository = $countryRepository;
        $this->addressFormatRepository = $addressFormatRepository;
        $this->subdivisionRepository = $subdivisionRepository;
    }

    public function getAddress($locale='fr-FR')
    {
        $country_code = substr($locale, 3, 2);

        $countryList = $this->countryRepository->getList($locale);

        // Get the country object for Brazil.
        $brazil = $this->countryRepository->get($country_code);
        // Get all country objects.
        $countries = $this->countryRepository->getAll();

        // Get the address format for Brazil.
        $addressFormat = $this->addressFormatRepository->get($country_code);

        // Get the subdivisions for Brazil.
        $states = $this->subdivisionRepository->getAll([$country_code]);
        $municipalities = [];
        foreach ($states as $state) {
            $municipalities = $state->getChildren();
        }

        return [
            'countryList' => $countryList,
            'countries' => $countries,
            'addressFormat' => $addressFormat,
            'states' => $states,
            'municipalities' => $municipalities,
        ];
    }

}