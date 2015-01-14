<?php
/**
 * Created by JetBrains PhpStorm.
 * User: pilotuser
 * Date: 21.09.12
 * Time: 10:39
 *
 */
class NewPaymentsKernelResponseError extends NewPaymentsKernel
{

    const ERROR_RESPONSE_CODE_UNAVAILABLE = 1;
    const ERROR_RESPONSE_CODE_INCORRECT_FORMAT = 2;
    const ERROR_RESPONSE_CODE_FATAL = 3; // Код в случае глобальной ошибки шлюза
    const ERROR_RESPONSE_CODE_PRODUCT_NOT_FOUND = 4;
    const ERROR_RESPONSE_CODE_PAYMENT_EXISTS = 5;
    const ERROR_RESPONSE_CODE_PAYMENT_TIME_OUT = 6;

    /**
     * @var array
     */
    public static $errorResponseCode = array(
        1 => 'Платеж не может быть совершен, неверные параметры', // Техническая ошибка на стороне ТСП
        2 => 'Неверный формат запроса', // Неверные параметры платежа (сумма, счет, прочее)
        3 => 'Платёж не может быть принят по административным причинам', // Платеж запрещен не по техническим причинам
        4 => 'Товара нет в наличии', // Товар временно отсутствует (актуально для специфичных товаров)
        5 => 'Платеж уже существует', //
        6 => 'Время подтверждения оплаты истекло' //
    );

    /**
     * @var
     */
    private $_code;

    /**
     * @var
     */
    private $_comment;


    /**
     *
     */
    public function __construct($test, $date)
    {
        parent::__construct($test, $date);

        $this->setCode(self::ERROR_RESPONSE_CODE_FATAL);
        $this->setComment(self::$errorResponseCode[$this->getCode()]);
    }

    /**
     * @param $code
     */
    public function setCode($code)
    {
        $this->_code = $code;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->_code;
    }

    /**
     * @param $comment
     */
    public function setComment($comment)
    {
        $this->_comment = $comment;
    }

    /**
     * @return mixed
     */
    public function getComment()
    {
        return $this->_comment;
    }

    public function asSimpleXMLElement()
    {
        $xml = new SimpleXMLElement2('<?xml version="1.0" encoding="UTF-8"?>
                                      <response test="" date="">
                                        <error>
                                            <code></code>
                                            <comment></comment>
                                        </error>
                                      </response>
                                    ');

        $xml['test'] = (bool)$this->getTest();
        $xml['date'] = $this->getDate()->format(DateTime::ISO8601);
        $xml->{'error'}->{'code'} = (string)$this->getCode();
        $xml->{'error'}->{'comment'} = (string)$this->getComment();

        return $xml;
    }

}
