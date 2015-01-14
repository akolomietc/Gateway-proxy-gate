<?php
/**
 * Created by JetBrains PhpStorm.
 * User: pilotuser
 * Date: 25.09.12
 * Time: 12:25
 *
 */
class Recipient
{
    /**
     * @var string
     */
    private $_contractId;

    /**
     * @var string
     */
    private $_productCode;

    /**
     * Имя аттрибута - идентификатора
     * @var string
     */
    private $_ident;

    /**
     * @var boolean
     */
    private $_identAttribute;

    /**
     * url - шлюза
     * @var string
     */
    private $_urlGate;

    /**
     * @var
     */
    private $_fee = 0;

    private $_termType = '';

    private $_termId = '';

    /**
     * @param  $fee
     */
    public function setFee($fee)
    {
        $this->_fee = $fee;
    }

    /**
     * @return
     */
    public function getFee()
    {
        return $this->_fee;
    }

    public function setTermId($termId)
    {
        $this->_termId = $termId;
    }

    public function getTermId()
    {
        return $this->_termId;
    }

    public function setTermType($termType)
    {
        $this->_termType = $termType;
    }

    public function getTermType()
    {
        return $this->_termType;
    }




    /**
     * @param $contractId
     */
    public function setContractId($contractId)
    {
        $this->_contractId = $contractId;
    }

    /**
     * @return mixed
     */
    public function getContractId()
    {
        return $this->_contractId;
    }

    /**
     * @param string $ident
     */
    public function setIdent($ident)
    {
        $this->_ident = $ident;
    }

    /**
     * @return string
     */
    public function getIdent()
    {
        return $this->_ident;
    }

    /**
     * @param boolean $identAttribute
     */
    public function setIdentAttribute($identAttribute)
    {
        $this->_identAttribute = $identAttribute;
    }

    /**
     * @return boolean
     */
    public function getIdentAttribute()
    {
        return $this->_identAttribute;
    }

    /**
     * @param string $urlGate
     */
    public function setUrlGate($urlGate)
    {
        $this->_urlGate = $urlGate;
    }

    /**
     * @return string
     */
    public function getUrlGate()
    {
        return $this->_urlGate;
    }

    /**
     * @param $productCode
     */
    public function setProductCode($productCode)
    {
        $this->_productCode = $productCode;
    }

    /**
     * @return mixed
     */
    public function getProductCode()
    {
        return $this->_productCode;
    }

    /**
     * @param $contractId
     * @param $productCode
     * @param $idIdent
     * @param $idIdentAttribute
     * @param $url
     */
    public function __construct($contractId, $productCode, $idIdent, $idIdentAttribute, $url)
    {
        $this->setContractId($contractId);
        $this->setProductCode($productCode);
        $this->setIdent($idIdent);
        $this->setIdentAttribute($idIdentAttribute);
        $this->setUrlGate($url);

    }

    /**
     * @param $providerData
     *
     * @return null|Recipient
     * @throws Exception
     */
    static public function getRecipientFromProviderData($providerData) {
        try {

            // TODO -KOLOMIETS: не очень красиво. отретушировать после тестирования
            $arrParams = explode('&', $providerData);
            $i = 0;
            while ($i < count($arrParams)) {
                $arrData = explode('=', $arrParams[$i]);
                $params[$arrData[0]] = $arrData[1];
                $i++;
            }

            if (!isset($params['contractId'])) {
                throw new Exception('contractId not found');
            }

            if (!isset($params['productCode'])) {
                throw new Exception('productCode not found');
            }

            if (!isset($params['ident'])) {
                throw new Exception('ident not found');
            }

            if (!isset($params['identAttribute'])) {
                throw new Exception('identAttribute not found');
            }


            global $config;
            if (!isset($params['urlGate']) or !isset($config['urlGate'][$params['urlGate']])) {
                throw new Exception('urlGate not found');
            }



            $recipient = new Recipient($params['contractId'], $params['productCode'], $params['ident'],
                $params['identAttribute'], $params['urlGate']);

            if ($params['urlGate'] == 'rapida') {


                if (!isset($params['TermType'])) {
                    if (!isset($params['termType'])) {
                        throw new Exception('TermType not found');
                    } else {
                        $recipient->setTermType($params['termType']);
                    }
                } else {
                    $recipient->setTermType($params['TermType']);
                }

                if (!isset($params['Fee'])) {
                    throw new Exception('Fee not found');
                }

                $recipient->setFee($params['Fee']);

                // TODO -KOLOMIETS: Убрать после тестирования
                //$params['termId'] = 'MCARD01';
                if (!isset($params['termId'])) {
                    throw new Exception('termId not found');
                }

                $recipient->setTermId($params['termId']);
            }

            return $recipient;

        } catch(Exception $e) {
            syslog(LOG_ERR, 'Error Recipient params: ' . $e->getMessage());
            return NULL;
        }

    }




}
