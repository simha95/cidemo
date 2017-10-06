<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Rest\Api\V2010\Account;

use Twilio\Options;
use Twilio\Values;

abstract class AddressOptions {
    /**
     * @param string $friendlyName The friendly_name
     * @param boolean $emergencyEnabled The emergency_enabled
     * @return CreateAddressOptions Options builder
     */
    public static function create($friendlyName = Values::NONE, $emergencyEnabled = Values::NONE) {
        return new CreateAddressOptions($friendlyName, $emergencyEnabled);
    }

    /**
     * @param string $friendlyName The friendly_name
     * @param string $customerName The customer_name
     * @param string $street The street
     * @param string $city The city
     * @param string $region The region
     * @param string $postalCode The postal_code
     * @param boolean $emergencyEnabled The emergency_enabled
     * @return UpdateAddressOptions Options builder
     */
    public static function update($friendlyName = Values::NONE, $customerName = Values::NONE, $street = Values::NONE, $city = Values::NONE, $region = Values::NONE, $postalCode = Values::NONE, $emergencyEnabled = Values::NONE) {
        return new UpdateAddressOptions($friendlyName, $customerName, $street, $city, $region, $postalCode, $emergencyEnabled);
    }

    /**
     * @param string $customerName The customer_name
     * @param string $friendlyName The friendly_name
     * @param string $isoCountry The iso_country
     * @return ReadAddressOptions Options builder
     */
    public static function read($customerName = Values::NONE, $friendlyName = Values::NONE, $isoCountry = Values::NONE) {
        return new ReadAddressOptions($customerName, $friendlyName, $isoCountry);
    }
}

class CreateAddressOptions extends Options {
    /**
     * @param string $friendlyName The friendly_name
     * @param boolean $emergencyEnabled The emergency_enabled
     */
    public function __construct($friendlyName = Values::NONE, $emergencyEnabled = Values::NONE) {
        $this->options['friendlyName'] = $friendlyName;
        $this->options['emergencyEnabled'] = $emergencyEnabled;
    }

    /**
     * The friendly_name
     * 
     * @param string $friendlyName The friendly_name
     * @return $this Fluent Builder
     */
    public function setFriendlyName($friendlyName) {
        $this->options['friendlyName'] = $friendlyName;
        return $this;
    }

    /**
     * The emergency_enabled
     * 
     * @param boolean $emergencyEnabled The emergency_enabled
     * @return $this Fluent Builder
     */
    public function setEmergencyEnabled($emergencyEnabled) {
        $this->options['emergencyEnabled'] = $emergencyEnabled;
        return $this;
    }

    /**
     * Provide a friendly representation
     * 
     * @return string Machine friendly representation
     */
    public function __toString() {
        $options = array();
        foreach ($this->options as $key => $value) {
            if ($value != Values::NONE) {
                $options[] = "$key=$value";
            }
        }
        return '[Twilio.Api.V2010.CreateAddressOptions ' . implode(' ', $options) . ']';
    }
}

class UpdateAddressOptions extends Options {
    /**
     * @param string $friendlyName The friendly_name
     * @param string $customerName The customer_name
     * @param string $street The street
     * @param string $city The city
     * @param string $region The region
     * @param string $postalCode The postal_code
     * @param boolean $emergencyEnabled The emergency_enabled
     */
    public function __construct($friendlyName = Values::NONE, $customerName = Values::NONE, $street = Values::NONE, $city = Values::NONE, $region = Values::NONE, $postalCode = Values::NONE, $emergencyEnabled = Values::NONE) {
        $this->options['friendlyName'] = $friendlyName;
        $this->options['customerName'] = $customerName;
        $this->options['street'] = $street;
        $this->options['city'] = $city;
        $this->options['region'] = $region;
        $this->options['postalCode'] = $postalCode;
        $this->options['emergencyEnabled'] = $emergencyEnabled;
    }

    /**
     * The friendly_name
     * 
     * @param string $friendlyName The friendly_name
     * @return $this Fluent Builder
     */
    public function setFriendlyName($friendlyName) {
        $this->options['friendlyName'] = $friendlyName;
        return $this;
    }

    /**
     * The customer_name
     * 
     * @param string $customerName The customer_name
     * @return $this Fluent Builder
     */
    public function setCustomerName($customerName) {
        $this->options['customerName'] = $customerName;
        return $this;
    }

    /**
     * The street
     * 
     * @param string $street The street
     * @return $this Fluent Builder
     */
    public function setStreet($street) {
        $this->options['street'] = $street;
        return $this;
    }

    /**
     * The city
     * 
     * @param string $city The city
     * @return $this Fluent Builder
     */
    public function setCity($city) {
        $this->options['city'] = $city;
        return $this;
    }

    /**
     * The region
     * 
     * @param string $region The region
     * @return $this Fluent Builder
     */
    public function setRegion($region) {
        $this->options['region'] = $region;
        return $this;
    }

    /**
     * The postal_code
     * 
     * @param string $postalCode The postal_code
     * @return $this Fluent Builder
     */
    public function setPostalCode($postalCode) {
        $this->options['postalCode'] = $postalCode;
        return $this;
    }

    /**
     * The emergency_enabled
     * 
     * @param boolean $emergencyEnabled The emergency_enabled
     * @return $this Fluent Builder
     */
    public function setEmergencyEnabled($emergencyEnabled) {
        $this->options['emergencyEnabled'] = $emergencyEnabled;
        return $this;
    }

    /**
     * Provide a friendly representation
     * 
     * @return string Machine friendly representation
     */
    public function __toString() {
        $options = array();
        foreach ($this->options as $key => $value) {
            if ($value != Values::NONE) {
                $options[] = "$key=$value";
            }
        }
        return '[Twilio.Api.V2010.UpdateAddressOptions ' . implode(' ', $options) . ']';
    }
}

class ReadAddressOptions extends Options {
    /**
     * @param string $customerName The customer_name
     * @param string $friendlyName The friendly_name
     * @param string $isoCountry The iso_country
     */
    public function __construct($customerName = Values::NONE, $friendlyName = Values::NONE, $isoCountry = Values::NONE) {
        $this->options['customerName'] = $customerName;
        $this->options['friendlyName'] = $friendlyName;
        $this->options['isoCountry'] = $isoCountry;
    }

    /**
     * The customer_name
     * 
     * @param string $customerName The customer_name
     * @return $this Fluent Builder
     */
    public function setCustomerName($customerName) {
        $this->options['customerName'] = $customerName;
        return $this;
    }

    /**
     * The friendly_name
     * 
     * @param string $friendlyName The friendly_name
     * @return $this Fluent Builder
     */
    public function setFriendlyName($friendlyName) {
        $this->options['friendlyName'] = $friendlyName;
        return $this;
    }

    /**
     * The iso_country
     * 
     * @param string $isoCountry The iso_country
     * @return $this Fluent Builder
     */
    public function setIsoCountry($isoCountry) {
        $this->options['isoCountry'] = $isoCountry;
        return $this;
    }

    /**
     * Provide a friendly representation
     * 
     * @return string Machine friendly representation
     */
    public function __toString() {
        $options = array();
        foreach ($this->options as $key => $value) {
            if ($value != Values::NONE) {
                $options[] = "$key=$value";
            }
        }
        return '[Twilio.Api.V2010.ReadAddressOptions ' . implode(' ', $options) . ']';
    }
}