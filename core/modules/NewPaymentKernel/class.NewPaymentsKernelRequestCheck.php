<?php
/**
 * Created by JetBrains PhpStorm.
 * User: pilotuser
 * Date: 20.09.12
 * Time: 16:33
 *
 */
class NewPaymentsKernelRequestCheck extends NewPaymentsKernelRequest
{
    /**
     * @var
     */
    private $_initialTime;

    /**
     * @param $initialTime
     */
    public function setInitialTime($initialTime)
    {
        $this->_initialTime = $initialTime;
    }

    /**
     * @return mixed
     */
    public function getInitialTime()
    {
        return $this->_initialTime;
    }

    /**
     * @param $test
     * @param DateTime $date
     * @param $providerData
     * @param $paymentId
     * @param $sum
     * @param $product
     * @param $initialTime
     */
    public function __construct($test, $date, $providerData, $paymentId, $sum, $product, $initialTime) {
        parent::__construct($test,$date,$providerData,$paymentId, $sum, $product);
    }


}
