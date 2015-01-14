<?php
/**
 * Created by JetBrains PhpStorm.
 * User: pilotuser
 * Date: 20.09.12
 * Time: 16:34
 *
 */
class NewPaymentsKernelResponse extends NewPaymentsKernel
{

    private $_comment;

    private $_sum;

    private $_currency = 643;

    /**
     * @var array
     */
    private $_attributes;

    private $_providerPaymentId;

    /**
     * @param array $attributes
     */
    public function setAttributes($attributes)
    {
        $this->_attributes = $attributes;
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        return $this->_attributes;
    }

    public function setComment($comment)
    {
        $this->_comment = $comment;
    }

    public function getComment()
    {
        return $this->_comment;
    }

    public function setCurrency($currency)
    {
        $this->_currency = $currency;
    }

    public function getCurrency()
    {
        return $this->_currency;
    }

    public function setProviderPaymentId($providerPaymentId)
    {
        $this->_providerPaymentId = $providerPaymentId;
    }

    public function getProviderPaymentId()
    {
        return $this->_providerPaymentId;
    }

    public function setSum($sum)
    {
        $this->_sum = $sum;
    }

    public function getSum()
    {
        return $this->_sum;
    }

    public function __construct($test, $date, $comment, $sum, $attributes)
    {
        parent::__construct($test, $date);

        $this->setComment($comment);
        $this->setSum($sum);
        $this->setAttributes($attributes);
    }

}
