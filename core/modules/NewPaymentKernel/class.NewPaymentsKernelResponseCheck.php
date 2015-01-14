<?php
/**
 * Created by JetBrains PhpStorm.
 * User: pilotuser
 * Date: 20.09.12
 * Time: 16:35
 *
 */
class NewPaymentsKernelResponseCheck extends NewPaymentsKernelResponse
{
    private $_providerInitialTime;

    private $_providerExpirationTime;

    public function setProviderExpirationTime($providerExpirationTime)
    {
        $this->_providerExpirationTime = $providerExpirationTime;
    }

    public function getProviderExpirationTime()
    {
        return $this->_providerExpirationTime;
    }

    public function setProviderInitialTime($providerInitialTime)
    {
        $this->_providerInitialTime = $providerInitialTime;
    }

    public function getProviderInitialTime()
    {
        return $this->_providerInitialTime;
    }


    public function __construct($test, $date, $comment, $sum, $attributes)
    {
        parent::__construct($test,$date,$comment,$sum, $attributes);
    }


    public function asSimpleXMLElement() {
        $xml = new SimpleXMLElement2('<?xml version="1.0" encoding="UTF-8"?>
                                      <response test="" date="">
                                        <check>
                                            <comment></comment>
                                            <sum currency="643"></sum>
                                        </check>
                                      </response>
                                    ');

        $xml['test'] = (string)$this->getTest();
        $xml['date'] = $this->getDate()->format(DateTime::ISO8601);

        $xml->{'check'}->{'comment'}    = $this->getComment();
        $xml->{'check'}->{'sum'}        = $this->getSum()*100;
        $xml->{'check'}->addChild($this->getAttributes());

        return $xml;
    }






}
