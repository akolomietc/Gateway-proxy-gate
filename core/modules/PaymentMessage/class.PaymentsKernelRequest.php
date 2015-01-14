<?php
/**
 * Created by JetBrains PhpStorm.
 * User: pilotuser
 * Date: 28.09.12
 * Time: 12:09
 */
class PaymentsKernelRequest
{
    /**
     * @param Recipient $recipient
     * @param NewPaymentsKernelRequestCheck $newRequestMessageCheck
     * @return null|PaymentsKernelXMLProtocolMessage
     */
    static public function checkRequest(Recipient $recipient, NewPaymentsKernelRequestCheck $newRequestMessageCheck)
    {
        try {

        $requestMessage = new PaymentsKernelXMLProtocolMessage(PaymentsKernelXMLProtocolMessage::TYPE_REQUEST,
            PaymentsKernelXMLProtocolMessage::FT_PAYMENTS_KERNEL,
            $recipient->getUrlGate());

        $requestMessage->setDocument($requestMessageDocument = new PaymentsKernelXMLProtocolMessageDocument);
        $requestMessageDocument->setTransactionId($newRequestMessageCheck->getPaymentId()); // transactionId == paymentId

        $requestMessageDocument->addTo($requestMessageDocumentTo = new
        PaymentsKernelXMLProtocolMessageDocumentParticipantTo($recipient->getContractId(), $recipient->getProductCode()));

        $requestMessageDocumentTo->setSum($newRequestMessageCheck->getSum());

        foreach ($newRequestMessageCheck->getAttributes() as $attrName => $attrValue) {
            if ((string)$attrName == $recipient->getIdent()) { // Если имя аттрибута является идентификатором в сообщении
                $requestMessageDocumentTo->setIdent((string)$attrValue);
                if ($recipient->getIdentAttribute()=='true') { // В случае, если идентификатор необходимо добавить и в аттрибуты сообщения
                    $requestMessageDocumentTo->addAttribute($attr = new PaymentsKernelXMLProtocolMessageDocumentAttribute((string)$attrName, (string)$attrValue));
                }
            } else {
                $requestMessageDocumentTo->addAttribute($attr = new PaymentsKernelXMLProtocolMessageDocumentAttribute((string)$attrName, (string)$attrValue));
            }
        }

            global $config;

        if ($recipient->getTermId() !== '' && $recipient->getTermId() !== 'MCARD01')  {
            if (isset($config['termId'][$recipient->getTermId()])) {
                $requestMessageDocument->addFrom($requestMessageDocumentFrom = new PaymentsKernelXMLProtocolMessageDocumentParticipantFrom(2, $config['termId'][$recipient->getTermId()]));
            } else {
                throw new Exception('unknown termId');
            }
        } else {
            $requestMessageDocument->addFrom($requestMessageDocumentFrom = new PaymentsKernelXMLProtocolMessageDocumentParticipantFrom(1, 1));
        }

        $requestMessageDocument->addFrom($requestMessageDocumentFrom = new PaymentsKernelXMLProtocolMessageDocumentParticipantFrom(1, 1));
        $requestMessageDocumentFrom->setIdent('');
        $requestMessageDocumentFrom->setSum($newRequestMessageCheck->getSum());
        $requestMessageDocument->setCustomElement($requestMessageDocumentCheck = new PaymentsKernelXMLProtocolMessageDocumentCheck);

            if ($recipient->getUrlGate() == 'rapida') {
                $requestMessageDocumentTo->setFee($recipient->getFee());
                $requestMessageDocumentFrom->setSum($newRequestMessageCheck->getSum() + $recipient->getFee());
            }


        $url = '';


            $branch = ServerConfig::getBranch();
            // Заглушка для тестов
            $protocol = 'https://';
            if ($branch == 'default') {
                // На тестовый шлюз
                $branch = 'test';
            }

            $url = $protocol . $recipient->getUrlGate() . '.gateways.' . $branch . '.localhost.ru';
            syslog(LOG_DEBUG, 'Gateway URL: ' . $url);
            $client = new Zend_Http_Client($url);
            $client->setMethod(Zend_Http_Client::POST);
            $client->setEncType('text/xml');
            $client->setRawData((string)$requestMessage);
            syslog(LOG_DEBUG, 'Gateway check request:' . PHP_EOL . $requestMessage);
            $response = $client->request();
            $body = $response->getBody();
            syslog(LOG_DEBUG, 'Gateway check response:' . PHP_EOL . $body);
            if (($responseMessage = PaymentsKernelXMLProtocolMessage::CreateFromRawXML($body))
                    && $responseMessageDocument = $responseMessage->getDocument() && $responseMessage->getDocument()->getTo()
            ) {
                return $responseMessage;
            }

            return NULL;

        } catch (Exception $e) {
            syslog(LOG_ERR, 'Http gateway check recipient: HTTP ERROR (' . $url . '): ' . $e->getMessage());
            return NULL;
        }
    }


    /**
     * @param Recipient $recipient
     * @param NewPaymentsKernelRequestPay $newRequestMessagePay
     * @return null|PaymentsKernelXMLProtocolMessage
     */
    static public function payRequest(Recipient $recipient, NewPaymentsKernelRequestPay $newRequestMessagePay)
    {

        $requestMessage = new PaymentsKernelXMLProtocolMessage(PaymentsKernelXMLProtocolMessage::TYPE_REQUEST,
            PaymentsKernelXMLProtocolMessage::FT_PAYMENTS_KERNEL, $recipient->getUrlGate());

        $requestMessage->setDocument($requestMessageDocument = new PaymentsKernelXMLProtocolMessageDocument);
        $requestMessageDocument->setTransactionId($newRequestMessagePay->getPaymentId());
        $requestMessageDocument->addTo($requestMessageDocumentTo =
            new PaymentsKernelXMLProtocolMessageDocumentParticipantTo($recipient->getContractId(),
                $recipient->getProductCode()));

        foreach ($newRequestMessagePay->getAttributes() as $attrName => $attrValue) {
            if ((string)$attrName == $recipient->getIdent()) { // Если имя аттрибута является идентификатором в сообщении
                $requestMessageDocumentTo->setIdent((string)$attrValue);
                if ($recipient->getIdentAttribute()) { // В случае, если идентификатор необходимо добавить и в аттрибуты сообщения
                    $requestMessageDocumentTo->addAttribute($attr = new PaymentsKernelXMLProtocolMessageDocumentAttribute((string)$attrName, (string)$attrValue));
                }
            } else {
                $requestMessageDocumentTo->addAttribute($attr = new PaymentsKernelXMLProtocolMessageDocumentAttribute((string)$attrName, (string)$attrValue));
            }
        }

        $requestMessageDocumentTo->setSum($newRequestMessagePay->getSum());


        if ($recipient->getTermId() !== '' && $recipient->getTermId() !== 'MCARD01')  {
            if (isset($config['termId'][$recipient->getTermId()])) {
                $requestMessageDocument->addFrom($requestMessageDocumentFrom = new PaymentsKernelXMLProtocolMessageDocumentParticipantFrom(2, $config['termId'][$recipient->getTermId()]));
            } else {
                throw new Exception('unknown termId');
            }
        } else {
            $requestMessageDocument->addFrom($requestMessageDocumentFrom = new PaymentsKernelXMLProtocolMessageDocumentParticipantFrom(1, 1));
        }

        $requestMessageDocumentFrom->setIdent('');
        $requestMessageDocumentFrom->setSum($newRequestMessagePay->getSum());

        $requestMessageDocument->setCustomElement($requestMessageDocumentPayment = new PaymentsKernelXMLProtocolMessageDocumentPayment);
        $requestMessageDocumentPayment->setControllerContractId($recipient->getContractId());
        $requestMessageDocumentPayment->setControllerPaymentId($newRequestMessagePay->getPaymentId());
        $requestMessageDocumentPayment->setRegistrationDate($newRequestMessagePay->getDate());
        $requestMessageDocumentPayment->setId($newRequestMessagePay->getPaymentId());

        $requestMessageDocumentFrom->setSum($newRequestMessagePay->getSum() + $newRequestMessagePay->getFee());

        if ($recipient->getUrlGate() == 'rapida') {
            $requestMessageDocumentTo->setFee($recipient->getFee());
            $requestMessageDocumentFrom->setSum($newRequestMessagePay->getSum() + $recipient->getFee());
        }

        $url = '';

        try {
            $branch = ServerConfig::getBranch();
            // Заглушка для тестов
            $protocol = 'https://';
            if ($branch == 'default') {
                // На тестовый шлюз
                $branch = 'test';
            }

            $url = $protocol . $recipient->getUrlGate() . '.gateways.' . $branch . '.localhost.ru';
            syslog(LOG_DEBUG, 'Gateway URL: ' . $url);
            $client = new Zend_Http_Client($url);
            $client->setMethod(Zend_Http_Client::POST);
            $client->setEncType('text/xml');
            $client->setRawData((string)$requestMessage);
            syslog(LOG_DEBUG, 'Gateway pay request:' . PHP_EOL . $requestMessage);
            $response = $client->request();
            $body = $response->getBody();
            syslog(LOG_DEBUG, 'Gateway pay response:' . PHP_EOL . $body);
            if (($responseMessage = PaymentsKernelXMLProtocolMessage::CreateFromRawXML($body))
                && $responseMessageDocument = $responseMessage->getDocument() && $responseMessage->getDocument()->getTo()
            ) {

                return $responseMessage;
            }

            return NULL;

        } catch (Exception $e) {
            syslog(LOG_ERR, 'Http gateway check recipient: HTTP ERROR (' . $url . '): ' . $e->getMessage());
            return NULL;
        }


    }


}
