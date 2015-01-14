<?php
/**
 * Created by JetBrains PhpStorm.
 * User: pilotuser
 * Date: 21.09.12
 * Time: 10:40
 */

require_once '../core/init/init.php';

// Пытаемся прочитать входящее сообщение
$rawRequest = file_get_contents('php://input');
syslog(LOG_DEBUG, 'Raw request:' . PHP_EOL . $rawRequest);

$branch = ServerConfig::getBranch();
if ($branch == 'production') {
    $test = false;
} else $test = true;

$newResponseMessage = new NewPaymentsKernelResponseError($test, new DateTime('now'));

if ($newRequestMessage = NewPaymentsKernelRequest::CreateFromRawXML($rawRequest)) {

    if ($newRequestMessage) {
        $className = get_class($newRequestMessage);
    } else {
        $className = 'unknown';
    }

    if ($recipient = Recipient::getRecipientFromProviderData($newRequestMessage->getProviderData())) {
        switch ($className) {
            case 'NewPaymentsKernelRequestCheck':
                syslog(LOG_DEBUG, 'check request');
                if ($responseMessageCheck = PaymentsKernelRequest::checkRequest($recipient, $newRequestMessage)) {
                    $newResponseMessage = PaymentsKernelResponse::checkResponse($newRequestMessage, $responseMessageCheck);
                }
                break;
            case 'NewPaymentsKernelRequestPay':
                syslog(LOG_DEBUG, 'pay request');
                if ($responseMessagePay = PaymentsKernelRequest::payRequest($recipient, $newRequestMessage)) {
                    $newResponseMessage = PaymentsKernelResponse::payResponse($newRequestMessage, $responseMessagePay);
                }
                break;
        }
    } else { // случай, если в базе не нашли продукт, либо база не работает
        $newResponseMessage->setTest($newRequestMessage->getTest());
        $newResponseMessage->setCode(NewPaymentsKernelResponseError::ERROR_RESPONSE_CODE_UNAVAILABLE);
        $newResponseMessage->setComment(NewPaymentsKernelResponseError::$errorResponseCode
            [NewPaymentsKernelResponseError::ERROR_RESPONSE_CODE_UNAVAILABLE]);
    }
}

echo $newResponseMessage->asSimpleXMLElement()->asXML();

