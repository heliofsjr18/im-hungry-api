<?php
/**
 * Created by PhpStorm.
 * User: Rafael Freitas
 * Date: 20/03/18
 * Time: 07:13
 */

require_once 'Connection/Conexao.php';
require_once "lib/vendor/autoload.php";
class CheckoutItensDAO
{
    public function generate($array_itens, $array_qtd){


        \PagSeguro\Library::initialize();
        \PagSeguro\Library::cmsVersion()->setName("Nome")->setRelease("1.0.0");
        \PagSeguro\Library::moduleVersion()->setName("Nome")->setRelease("1.0.0");
        \PagSeguro\Configuration\Configure::setCharset('UTF-8');// UTF-8 or ISO-8859-1
        //\PagSeguro\Configuration\Configure::setLog(true, '/logpath/pagseguro.log');

        \PagSeguro\Configuration\Configure::setEnvironment('sandbox');//production or sandbox
        \PagSeguro\Configuration\Configure::setAccountCredentials(
            /*E-Mail*/
            'pagseguro@rafafreitas.com',

            /*sandbox*/
            'E6D827F59A0A46488AB467A4BDB4A43E'

            /*Produção*/
            //'0C72863036CA4D8E9B7486AC70BA8DE7'
        );




    }
}