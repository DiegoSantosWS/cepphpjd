

## DESCRIÇÃO

Essa é uma pais para consultar endereços através de um cep

## FORMA DE USO

CLONE O REPOSITORIO PARA SUA MAQUINA
``` bash
$ git clone https://github.com/DiegoSantosWS/cepphpjd.git

```
## ACESSANDO

ABRA SEU BROWSER E COLE A SEGUINTE URL http://localhost/wsitecep/?cep=69005-220
``` json
{
"bairro": "Centro",
"cidade": "Manaus",
"logradouro": "Rua Lima Bacuri",
"cep": "69005220",
"estado": "AM"
}
```
Caso esteja via linux pode fazer test com CURL ou HTTP

``` bash
$ curl GET http://localhost/wsitecep/?cep=69005220
```
---
``` bash
$ http GET http://localhost/wsitecep/?cep=69005220
```
## CODE
Entenda o como funciona. Abaixo eu estou mostrando o codigo da index, mas é bom dar uma olhada na 
[classe cep](https://bitbucket.org/wsitebrasil/wsitecep/src/8005b7fb387da53a409f21d11167b1325dd2b715/CEP.class.php#lines-9) lá vai entender as informações.

``` php
header('Content-Type: application/json'); //seta o header
include_once ("CEP.class.php"); //recebe a class

$dados = new WsiteCEP(); //analise essa classe e ira entender o processo interno
$cep = $_REQUEST["cep"]; //recebe o paramentro no caso o cep
$dados->getCep($cep); // vamos trabalhar
$dados->runCep(); // retorna os dados
```
## AVISO

Caso encontre alguma correção, crie um branch e faça um pull request para ser analizado. ou envie um email para diegosantosws@gmail.com
