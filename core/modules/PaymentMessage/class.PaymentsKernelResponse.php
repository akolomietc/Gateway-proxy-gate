<?php
/**
 * Created by JetBrains PhpStorm.
 * User: pilotuser
 * Date: 01.10.12
 * Time: 14:56
 */
class PaymentsKernelResponse
{

    /**
     * @param NewPaymentsKernelRequestCheck $newRequestMessageCheck
     * @param PaymentsKernelXMLProtocolMessage $responseMessageCheck
     * @return NewPaymentsKernelResponseCheck|NewPaymentsKernelResponseError
     */
    static public function checkResponse(NewPaymentsKernelRequestCheck $newRequestMessageCheck,
                                         PaymentsKernelXMLProtocolMessage $responseMessageCheck)
    {

        switch ($responseMessageCheck->getDocument()->getTo()->getStatus()) {
            case PaymentsKernelXMLProtocolMessageDocumentParticipant::STATUS_ACTIVE:
                $responseCheck = new NewPaymentsKernelResponseCheck($newRequestMessageCheck->getTest(),
                    new DateTime('now'), 'Параметры платежа верны', $newRequestMessageCheck->getSum(),
                    $newRequestMessageCheck->getRawAttributes());
                break;
            case PaymentsKernelXMLProtocolMessageDocumentParticipant::STATUS_IDENT_NOT_FOUND:
                $responseCheck = new NewPaymentsKernelResponseError($newRequestMessageCheck->getTest(), new DateTime('now'));
                $responseCheck->setComment(NewPaymentsKernelResponseError::ERROR_RESPONSE_CODE_PRODUCT_NOT_FOUND);
                $responseCheck->setCode(NewPaymentsKernelResponseError::$errorResponseCode
                [NewPaymentsKernelResponseError::ERROR_RESPONSE_CODE_PRODUCT_NOT_FOUND]);
                break;
            default:
                $responseCheck = new NewPaymentsKernelResponseError($newRequestMessageCheck->getTest(), new DateTime('now'));
                $responseCheck->setCode(NewPaymentsKernelResponseError::ERROR_RESPONSE_CODE_PRODUCT_NOT_FOUND);
                $responseCheck->setComment(NewPaymentsKernelResponseError::$errorResponseCode
                [NewPaymentsKernelResponseError::ERROR_RESPONSE_CODE_PRODUCT_NOT_FOUND]);
        }

        return $responseCheck;
    }


    /**
     * @param NewPaymentsKernelRequestPay $newRequestMessagePay
     * @param PaymentsKernelXMLProtocolMessage $responseMessagePay
     * @return NewPaymentsKernelResponseError|NewPaymentsKernelResponsePay
     */
    static public function payResponse(NewPaymentsKernelRequestPay $newRequestMessagePay,
                                       PaymentsKernelXMLProtocolMessage $responseMessagePay)
    {

        switch ($responseMessagePay->getDocument()->getTo()->getStatus()) {
            case PaymentsKernelXMLProtocolMessageDocumentCheck::STATUS_ACTIVE:
                $responseCheck = new NewPaymentsKernelResponsePay($newRequestMessagePay->getTest(),
                    new DateTime('now'), 'Платеж выполнен успешно', $newRequestMessagePay->getSum(),
                    $responseMessagePay->getDocument()->getTo()->getOperationId(),
                    $responseMessagePay->getDocument()->getTo()->getPostingDate(),
                    $newRequestMessagePay->getRawAttributes());
                break;
            case PaymentsKernelXMLProtocolMessageDocumentParticipant::STATUS_IDENT_NOT_FOUND:
                $responseCheck = new NewPaymentsKernelResponseError($newRequestMessagePay->getTest(), new DateTime('now'));
                $responseCheck->setComment(NewPaymentsKernelResponseError::ERROR_RESPONSE_CODE_PRODUCT_NOT_FOUND);
                $responseCheck->setCode(NewPaymentsKernelResponseError::$errorResponseCode
                [NewPaymentsKernelResponseError::ERROR_RESPONSE_CODE_PRODUCT_NOT_FOUND]);
                break;
            default:
                $responseCheck = new NewPaymentsKernelResponseError($newRequestMessagePay->getTest(), new DateTime('now'));
                $responseCheck->setComment(NewPaymentsKernelResponseError::ERROR_RESPONSE_CODE_PRODUCT_NOT_FOUND);
                $responseCheck->setCode(NewPaymentsKernelResponseError::$errorResponseCode
                [NewPaymentsKernelResponseError::ERROR_RESPONSE_CODE_PRODUCT_NOT_FOUND]);
        }

        return $responseCheck;


    }

}
