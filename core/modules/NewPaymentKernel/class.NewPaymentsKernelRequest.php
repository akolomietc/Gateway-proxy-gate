<?php
/**
 * Created by JetBrains PhpStorm.
 * User: pilotuser
 * Date: 20.09.12
 * Time: 16:15
 *
 */
class NewPaymentsKernelRequest extends NewPaymentsKernel
{


    /**
     * @var
     */
    private $_paymentId;

    /**
     * @var
     */
    private $_sum;

    /**
     * @var
     */
    private $_currency;

    /**
     * @var
     */
    private $_product;

    /**
     * @var array
     */
    private $_attributes = Array();

    private $_rawAttributes;

    public function setRawAttributes($rawAttributes)
    {
        $this->_rawAttributes = $rawAttributes;
    }

    public function getRawAttributes()
    {
        return $this->_rawAttributes;
    }

    /**
     * Дополнительная информация для провайдера
     * @var string
     */
    private $_providerData = '';

    /**
     * @param $name
     * @param $value
     */
    protected function addAttributes($name, $value)
    {
        $this->_attributes[(string)$name] = (string)$value;
    }

    /**
     * @param $name
     * @return null|string
     */
    public function getAttribute($name)
    {
        if(isset($this->_attributes[(string)$name])) {
            return (string)$this->_attributes[(string)$name];
        } else {
            return NULL;
        }
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        return $this->_attributes;
    }

    /**
     * @param array $attributes
     */
    protected function setAttributes(array $attributes)
    {
        $this->_attributes = $attributes;
    }

    /**
     * @param $currency
     */
    public function setCurrency($currency)
    {
        $this->_currency = $currency;
    }

    /**
     * @return mixed
     */
    public function getCurrency()
    {
        return $this->_currency;
    }

    /**
     * @param $paymentId
     */
    public function setPaymentId($paymentId)
    {
        $this->_paymentId = $paymentId;
    }

    /**
     * @return mixed
     */
    public function getPaymentId()
    {
        return $this->_paymentId;
    }

    /**
     * @param string $providerData
     */
    public function setProviderData($providerData)
    {
        $this->_providerData = $providerData;
    }

    /**
     * @return string
     */
    public function getProviderData()
    {
        return $this->_providerData;
    }

    /**
     * @param $product
     */
    public function setProduct($product)
    {
        $this->_product = $product;
    }

    /**
     * @return mixed
     */
    public function getProduct()
    {
        return $this->_product;
    }

    /**
     * @param $sum
     */
    public function setSum($sum)
    {
        $this->_sum = $sum;
    }

    /**
     * @return mixed
     */
    public function getSum()
    {
        return $this->_sum;
    }


    /**
     *  Конструктор
     */
    public function __construct($test, $date, $providerData, $paymentId, $sum, $product)
    {
        parent::__construct($test, $date);

        $this->setPaymentId($paymentId);
        $this->setSum($sum);
        $this->setProduct($product);
        $this->setProviderData($providerData);
    }


    /**
     * Попытаться создать объект сообщения из XML строки
     * @param string $rawXml - Строка с XML
     * @return \NewPaymentsKernelRequestCheck|\NewPaymentsKernelRequestPay|null
     */
    static public function CreateFromRawXML($rawXml)
    {
        try {
            $xml = @new SimpleXMLElement2($rawXml);
            return NewPaymentsKernelRequest::CreateFromSimpleXMLElement($xml);
        } catch(Exception $e) {
            syslog(LOG_ERR, 'Can not create message from xml: ' . $e->getMessage());
            return NULL;
        }
    }


    /**
     * Попытаться создать объект сообщения из SimpleXMLElement
     * @param  SimpleXMLElement2 $xml - Объект SimpleXMLElement
     * @return \NewPaymentsKernelRequestCheck|\NewPaymentsKernelRequestPay|null
     * @throws Exception
     */
    static public function CreateFromSimpleXMLElement(SimpleXMLElement2 $xml)
    {
        try {
            try {
                if($xml->getName() != 'request') {
                    throw new Exception('Not request');
                }

                if(empty($xml['test'])) {
                    throw new Exception('Empty test');
                }

                if(empty($xml['date'])) {
                    throw new Exception('Empty date');
                }

                $date = new DateTime($xml['date']);

                $providerData = '';
                if(!empty($xml->providerData)) {
                    $providerData = $xml->providerData;
                }

                if (!empty($xml->{'check'})) {
                    $typeRequest = $xml->{'check'};
                } elseif (!empty($xml->{'pay'})) {
                    $typeRequest = $xml->{'pay'};
                } else {
                    throw new Exception('Empty typeRequest');
                }

                if(empty($typeRequest->{'paymentId'})) {
                    throw new Exception('Empty paymentId');
                }

                if(empty($typeRequest->{'sum'})) {
                    throw new Exception('Empty sum');
                }

//                if(empty($typeRequest->{'fee'})) {
//                    throw new Exception('Empty fee');
//                }

                if(empty($typeRequest->{'product'})) {
                    throw new Exception('Empty product');
                }

                $attributes = Array();
                if(!empty($typeRequest->{'attributes'}->{'attribute'})) { // считываем аттрибуты
                    foreach($typeRequest->{'attributes'}->{'attribute'} as $attribute) {
                        if (isset($attribute['id']) && (string)$attribute['id'] != 'Amount') {
                            $attributes[(string)$attribute['id']] = (string)$attribute;
                        }
                    }
                }

                if ($typeRequest->getName() == 'check') {

                    if(empty($typeRequest->{'initialTime'})) {
                        throw new Exception('Empty initialTime');
                    }

                    $initialTime = new DateTime($typeRequest->{'initialTime'});

                    $requestCheck = new NewPaymentsKernelRequestCheck($xml['test'], $date, $providerData,
                        $typeRequest->{'paymentId'}, bcdiv($typeRequest->{'sum'}, 100, 2),
                        $typeRequest->{'product'}, $initialTime);
                    $requestCheck->setRawAttributes($typeRequest->{'attributes'});

                    $requestCheck->setAttributes($attributes);

                    return $requestCheck;

                } elseif($typeRequest->getName() == 'pay') {
                    if(empty($typeRequest->{'paymentTime'})) {
                        throw new Exception('Empty paymentTime');
                    }

                    if(empty($typeRequest->{'sum'}['fee'])) {
                        throw new Exception('Empty fee');
                    }



                    $requestPay = new NewPaymentsKernelRequestPay($xml['test'], $date, $providerData,
                        $typeRequest->{'paymentId'}, bcdiv($typeRequest->{'sum'}, 100, 2),
                        $typeRequest->{'product'}, $typeRequest->{'paymentTime'}, bcdiv($typeRequest->{'sum'}['fee'], 100, 2));

                    $requestPay->setAttributes($attributes);
                    $requestPay->setRawAttributes($typeRequest->{'attributes'});

                    return $requestPay;
                } else {
                    throw new Exception('Fail type request: not check, not pay');
                }

            } catch(Exception $e) {
                //TODO -KOLOMIETS: Поразбираться с порядком exception
                throw new Exception('Incorrect message protocol: ' . $e->getMessage());
            }

        } catch(Exception $e) {
            syslog(LOG_ERR, 'Can not create message from xml: ' . $e->getMessage());
            return NULL;
        }
    }

}
