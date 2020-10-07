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

    public static function getString($value, $body = null): string
    {
        switch ($value) {
            case Exception::SERVER_ERROR:
                return 'Houve um erro ao executar a ação, favor entrar em contato conosco';

                break;

            case Exception::UNAUTHORIZED:
            case Exception::TOKEN_INVALID:
                return 'Não Autorizado';

                break;

            case Exception::PASSWORD_INCORRECT:
                return 'Senha incorreta';

                break;

            case Exception::INCORRECT_LOGIN:
                return 'Dados de acesso incorretos';

                break;

            case Exception::EMAIL_IN_USE:
                return 'Este email já está em uso';

                break;

            case Exception::CPF_IN_USE:
                return 'Este CPF já está em uso';

                break;

            case Exception::CNPJ_IN_USE:
                return 'Este CNPJ já está em uso';

                break;

            case Exception::LOGIN_IN_USE:
                return 'Este login já está em uso';

                break;

            case Exception::DATA_ERROR:
                // Primeiro Erro
                return array_values($body)[0][0];

                break;

            case Exception::DATA_ERROR_GENERAL:
                return 'Dados da requisição incorretos';

                break;

            case Exception::DATA_USER_TYPE_NOT_FOUND:
                return 'Tipo de Usuário não cadastrado';

                break;

            case Exception::DATA_PROPERTIES_TYPE_NOT_FOUND:
                return 'Tipo de imóvel não cadastrado';

                break;

            case Exception::NOT_FOUND_RESOURCE:
                return 'Recurso não encontrado';

                break;

            //Transactionsconst
            case Exception::UPLOAD_FILE_ERROR:
                return 'Houve um erro ao fazer o upload do arquivo';

                break;

            case Exception::UPLOAD_FILE_NOT_ALLOWED:
                return 'Tipo de arquivo não permitido';

                break;

            case Exception::REALESTATE_LIMIT_PROPERTIES_EXCEEDED:
                return 'Limite de imóveis por dia excedido';

                break;

            case Exception::REALESTATE_LIMIT_IMAGES_EXCEEDED:
                return 'Limite de imagens por dia excedido';

                break;

            //Properties
            case Exception::PROPERTIES_COD_EXIST:
                return 'Este código já está em uso';

                break;

            case Exception::PROPERTIES_COD_BDI_NOT_USE:
                return 'O prefixo BDI é reservado para o Banco dos Imóveis';

                break;

            //Contatos
            case Exception::CONTACTS_COD_EXIST:
                return 'Este código já está em uso';

                break;

            case Exception::CONTACTS_PHONE_IN_USE:
                return 'Este telefone já está em uso';

                break;

            case Exception::CONTACTS_EMAIL_IN_USE:
                return 'Este email já está em uso';

                break;

            case Exception::FUNNEL_STEP_NOT_EMPTY:
                return 'Etapa do funil não está vazia. É necessário transferir contatos para outra etapa';

                break;

            case Exception::FUNNEL_STEP_EMPTY:
                return 'Não existe nenhuma etapa do funil para essa imobiliária';

                break;

            case Exception::FUNNEL_STEP_EXIST:
                return 'Já existe uma etapa do funil com esse nome';

                break;

            //Equivalencia
            case Exception::LOCATION_EXIST:
                return 'Localização já existe';

                break;

            //Importação
            case Exception::IMPORT_ELEMENT_DUPLICATE_LISTING_ID:
                return 'Código de imóvel duplicado';

                break;

            case Exception::IMPORT_NO_USER_MASTER_FOUND:
                return 'Não existe dono de imobiliária';

                break;

            case Exception::IMPORT_ELEMENT_NOT_FOUND_LISTING_ID:
                return 'A tag LISTINGID não foi encontrada';

                break;

            case Exception::IMPORT_ELEMENT_NOT_FOUND_TRANSACTION_TYPE:
                return 'A tag TRANSACTIONTYPE não foi encontrada';

                break;

            case Exception::IMPORT_ELEMENT_NOT_FOUND_STATE:
                return 'A tag STATE não foi encontrada';

                break;

            case Exception::IMPORT_ELEMENT_NOT_FOUND_CITY:
                return 'A tag CITY não foi encontrada';

                break;

            case Exception::IMPORT_ELEMENT_NOT_FOUND_NEIGHBORHOOD:
                return 'A tag NEIGHBORHOOD não foi encontrada';

                break;

            case Exception::IMPORT_ELEMENT_NOT_FOUND_PROPERTY_TYPE:
                return 'A tag PROPERTYTYPE não foi encontrada';

                break;

            case Exception::IMPORT_INCORRECT_PROPERTY_TYPE:
                return 'O valor da tag PROPERTYTYPE está incorreta';

                break;

            case Exception::IMPORT_INCORRECT_TRANSACTION_TYPE:
                return 'O valor da tag TRANSACTIONTYPE está incorreta';

                break;

            case Exception::IMPORT_ELEMENT_NOT_FOUND_LISTINGS:
                return 'A tag LISTINGS não foi encontrada';

                break;

            case Exception::IMPORT_XML_ERROR:
                return 'O XML possui erros em sua sintaxe';

                break;

            case Exception::IMPORT_XML_NOT_FOUND:
                return 'O XML não foi encontrado';

                break;

            case Exception::PARTNERSHIP_CHAT_PROPERTY_ME:
                return 'Não é possivel criar uma conversa com seus próprios imóveis';

                break;

            default:
                return 'Erro desconhecido';

                break;
        }
    }

    /**
     * @param string $type
     *
     * @throws Exception
     */
    public static function NotFound($type = '')
    {
        Exception::exception(Exception::NOT_FOUND_RESOURCE);
    }

    /**
     * @throws Exception
     */
    public static function Unauthorized()
    {
        Exception::exception(Exception::UNAUTHORIZED);
    }

    /**
     * @throws Exception
     */
    public static function InvalidToken()
    {
        Exception::exception(Exception::TOKEN_INVALID);
    }

    /**
     * @param int $errorCode
     * @param $body
     *
     * @throws Exception
     */
    public static function Exception(int $errorCode, $body = null)
    {
        throw new Exception(Exception::getString($errorCode, $body), $errorCode, null, $body, );
    }

    /**
     * Retorna código http, dependendo do erro.
     *
     * @param  $value
     * @return int
     */
    public static function getHttpCode($value)
    {
        switch ($value) {
            case Exception::UNAUTHORIZED:
            case Exception::TOKEN_INVALID:
                return 401;

            case Exception::DATA_ERROR:
                return 422;
                break;

            case Exception::NOT_FOUND_RESOURCE:
                return 404;
                break;

            default:
                return 500;
        }
    }

    public function getBody()
    {
        return $this->body;
    }
}
