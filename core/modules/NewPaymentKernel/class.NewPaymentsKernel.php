<?php
/**
 * Created by JetBrains PhpStorm.
 * User: pilotuser
 * Date: 20.09.12
 * Time: 16:14
 */
abstract class NewPaymentsKernel
{
    /**
     * Формат даты
     */
    const DATE_FORMAT = 'Y-m-d\TH:i:s';

    public function __construct($test, DateTime $date)
    {
        $this->setTest($test);
        $this->setDate($date);
    }

    /**
     * Признак тестового режима
     * @var boolean
     */
    private $_test;

    /**
     * Время формирования ответа/запроса
     * @var DateTime
     */
    private $_date;

    /**
     * @param $date
     */
    public function setDate($date)
    {
        $this->_date = $date;
    }

    /**
     * @return DateTime
     */
    public function getDate()
    {
        return $this->_date;
    }

    /**
     * @param $test
     */
    public function setTest($test)
    {
        $this->_test = $test;
    }

    /**
     * @return bool
     */
    public function getTest()
    {
        return $this->_test;
    }

}
