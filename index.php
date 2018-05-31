<?php
error_reporting(E_ALL || E_PARSE);
/**
 * AUTHOR: <name>DIEGO DOS SANTOS</name>
 * contact <diego@wsitebrasil.com.br>
 */
header('Content-Type: application/json');
//header('Content-Type: text/html');


include_once ("CEP.class.php");

$dados = new WsiteCEP();
$cep = $_REQUEST["cep"];
$dados->getCep($cep);
$dados->runCep();

