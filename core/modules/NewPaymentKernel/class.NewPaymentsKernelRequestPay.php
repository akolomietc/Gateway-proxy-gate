<?php
/**
 * Created by JetBrains PhpStorm.
 * User: pilotuser
 * Date: 20.09.12
 * Time: 16:37
 *
 */
class NewPaymentsKernelRequestPay extends NewPaymentsKernelRequest
{
    /**
     * @var
     */
    private $_paymentTime;

    /**
     * @var
     */
    private $_providerPaymentId;

    /**
     * @var
     */
    private $_providerInitialTime;

    private $_fee;

    public function setFee($fee)
    {
        $this->_fee = $fee;
    }

    public function getFee()
    {
        return $this->_fee;
    }

    /**
     * @param $providerInitialTime
     */
    public function setProviderInitialTime($providerInitialTime)
    {
        $this->_providerInitialTime = $providerInitialTime;
    }

    /**
     * @return mixed
     */
    public function getProviderInitialTime()
    {
        return $this->_providerInitialTime;
    }

    /**
     * @param $providerPaymentId
     */
    public function setProviderPaymentId($providerPaymentId)
    {
        $this->_providerPaymentId = $providerPaymentId;
    }

    /**
     * @return mixed
     */
    public function getProviderPaymentId()
    {
        return $this->_providerPaymentId;
    }

    /**
     * @param $paymentTime
     */
    public function setPaymentTime($paymentTime)
    {
        $this->_paymentTime = $paymentTime;
    }

    /**
     * @return mixed
     */
    public function getPaymentTime()
    {
        return $this->_paymentTime;
    }

    public function __construct($test, $date, $providerData, $paymentId, $sum, $product, $paymentTime, $fee) {
        parent::__construct($test,$date,$providerData,$paymentId, $sum, $product);

        $this->setFee($fee);
    }




}
