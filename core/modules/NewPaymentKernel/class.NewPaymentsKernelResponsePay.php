<?php
/**
 * Created by JetBrains PhpStorm.
 * User: pilotuser
 * Date: 20.09.12
 * Time: 16:40
 *
 */
class NewPaymentsKernelResponsePay extends NewPaymentsKernelResponse
{
    private $_providerPaymentTime;

    private $_providerPaymentId;

    public function setProviderPaymentId($providerPaymentId)
    {
        $this->_providerPaymentId = $providerPaymentId;
    }

    public function getProviderPaymentId()
    {
        return $this->_providerPaymentId;
    }

    public function setProviderPaymentTime($providerPaymentTime)
    {
        $this->_providerPaymentTime = $providerPaymentTime;
    }

    public function getProviderPaymentTime()
    {
        return $this->_providerPaymentTime;
    }

    public function __construct($test, $date, $comment, $sum, $providerPaymentId, $providerPaymentTime, $attributes)
    {
        parent::__construct($test, $date, $comment, $sum, $attributes);

        $this->setProviderPaymentTime($providerPaymentTime);
        $this->setProviderPaymentId($providerPaymentId);
    }

    public function asSimpleXMLElement() {
        $xml = new SimpleXMLElement2('<?xml version="1.0" encoding="UTF-8"?>
                                      <response test="" date="">
                                        <pay>
                                            <comment></comment>
                                            <sum currency="643"></sum>
                                            <provider>
                                                <paymentId></paymentId>
                                            </provider>
                                        </pay>
                                      </response>
                                    ');

        $xml['test'] = (string)$this->getTest();
        $xml['date'] = $this->getDate()->format(DateTime::ISO8601);
        $xml->{'pay'}->{'comment'} = $this->getComment();
        $xml->{'pay'}->{'sum'} = $this->getSum()*100;
        $xml->{'pay'}->{'provider'}->{'paymentId'} = $this->getProviderPaymentId();
        //$xml->{'pay'}->{'provider'}->{'paymentTime'} = $this->getProviderPaymentTime();
        $xml->{'pay'}->addChild($this->getAttributes());

        return $xml;
    }





}
