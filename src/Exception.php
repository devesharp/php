<?php

namespace Devesharp;

use Throwable;

/**
 * Exception
 */
class Exception extends \Exception
{
    const SERVER_ERROR = 1;
    const UNAUTHORIZED = 2;
    const TOKEN_INVALID = 3;
    const INCORRECT_LOGIN = 4;
    const PASSWORD_INCORRECT = 5;
    const PERMISSION_NOT_EXIST = 6;
    const NOT_IMPLEMENTED = 100;

    // Em uso
    const EMAIL_IN_USE = 1000;
    const CPF_IN_USE = 1001;
    const CNPJ_IN_USE = 1002;
    const LOGIN_IN_USE = 1003;

    // Erro nas informações enviadas
    const DATA_ERROR = 2000;
    const DATA_ERROR_GENERAL = 2001;

    const DATA_USER_TYPE_NOT_FOUND = 2200;
    const DATA_PROPERTIES_TYPE_NOT_FOUND = 2210;

    const NOT_FOUND_RESOURCE = 3000;

    const UPLOAD_FILE_ERROR = 4000;
    const UPLOAD_FILE_NOT_ALLOWED = 4001;

    const REALESTATE_LIMIT_PROPERTIES_EXCEEDED = 5000;
    const REALESTATE_LIMIT_IMAGES_EXCEEDED = 5001;

    // Properties
    const PROPERTIES_COD_EXIST = 6000;
    const PROPERTIES_COD_BDI_NOT_USE = 6001;

    // Contato
    const CONTACTS_COD_EXIST = 7000;
    const CONTACTS_PHONE_IN_USE = 7001;
    const CONTACTS_EMAIL_IN_USE = 7002;

    // Funil de Vendas
    const FUNNEL_STEP_NOT_EMPTY = 8000;
    const FUNNEL_STEP_EMPTY = 8001;
    const FUNNEL_STEP_EXIST = 8002;

    // Equivalencia
    const LOCATION_EXIST = 9000;

    // Importação
    const IMPORT_ELEMENT_NOT_FOUND_LISTING_ID = 10000;
    const IMPORT_ELEMENT_NOT_FOUND_TRANSACTION_TYPE = 10001;
    const IMPORT_ELEMENT_NOT_FOUND_STATE = 10002;
    const IMPORT_ELEMENT_NOT_FOUND_CITY = 10003;
    const IMPORT_ELEMENT_NOT_FOUND_NEIGHBORHOOD = 10004;
    const IMPORT_ELEMENT_NOT_FOUND_PROPERTY_TYPE = 10005;
    const IMPORT_ELEMENT_NOT_FOUND_LISTINGS = 10006;
    const IMPORT_INCORRECT_PROPERTY_TYPE = 10007;
    const IMPORT_INCORRECT_TRANSACTION_TYPE = 10008;
    const IMPORT_XML_ERROR = 10009;
    const IMPORT_XML_NOT_FOUND = 10010;
    const IMPORT_ELEMENT_DUPLICATE_LISTING_ID = 10011;
    const IMPORT_NO_USER_MASTER_FOUND = 10012;
    const IMPORT_ELEMENT_NOT_FOUND_IMAGES = 10013;

    // Exportação
    const EXPORT_NOT_FOUND_PORTAL = 11000;

    // Chat de Parceria
    const PARTNERSHIP_CHAT_PROPERTY_ME = 12000;

    public $body;

    public function __construct(
        $message = '',
        $code = 0,
        Throwable $previous = null,
        $body = null
    ) {
        parent::__construct($message, $code, $previous);
        $this->body = $body;
    }


    public function getBody()
    {
        return $this->body ?? null;
    }
}
