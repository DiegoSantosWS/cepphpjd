<?php
/**
 * AUTHOR: <name>DIEGO DOS SANTOS</name>
 * contact <diego@wsitebrasil.com.br>
 */
include_once ("DaoCEP.class.php");
class WsiteCEP {
	
	public $cep;
	const BASE_URL = "http://api.postmon.com.br/v1/cep";
	/**
	 * getCep recebe o cep a ser consultado
	 *
	 * @param [type] $cep
	 * @return void
	 */
	public function getCep($cep) {
		$this->cep = self::soNumero($cep);
	}
	/**
	 * Retorna somente numeros sem strings
	 *
	 * @param [numeric] $int
	 * @return int
	 */
	public static function soNumero($int) {
		return preg_replace('[^0-9|-]', '', $int);
	}
	/**
	 * Retorna o cep para consulta
	 *
	 * @param [type] $cep
	 * @return void
	 */
	public function setCep() {
		return $this->cep;
	}
	/**
	 * Retorna o status da url
	 *
	 * @param [string] $url
	 * @return int
	 */
	private function statusCOD($url) { 
		if($ch = curl_init($url)) { 
		 	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET'); 
		   	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
		   	curl_setopt($ch, CURLOPT_POSTFIELDS, $url); 
		   	curl_exec($ch); 
		   	$status = curl_getinfo($ch, CURLINFO_HTTP_CODE); 
		   	curl_close($ch); 
		   	return (int) $status; 
		} else { 
			return false; 
		} 
 	} 
	/**
	 * Retorta dados consultados
	 *
	 * @param [string] $URL
	 * @return void
	 */
	private function curl_get_file_contents($URL) {
		$c = curl_init();
		curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($c, CURLOPT_URL, $URL);
		$contents = curl_exec($c);
		curl_close($c);
	
		if ($contents){
			return utf8_encode($contents);
		} else {
			return false;
		}
	}
	/**
	 * Devolve json formatado
	 *
	 * @param [type] $resposta
	 * @return void
	 */
	private function respotaJSON($resposta) {
		foreach ($resposta as $key=>$val) {
			$resposta[$key] = $val;
		}
		echo $_GET['callback'] . '' . json_encode($resposta) . '';
	}
	/**
	 * Retorna o json para ser consumido
	 *
	 * @return void
	 */
	public function runCep() {
		$url = sprintf("%s/%s", self::BASE_URL, self::setCep());
        if (self::statusCOD($url) == 200) {
			$ceps = self::curl_get_file_contents($url);
			$jsonCEPs = json_decode($ceps);
			$resposta = [];
			$resposta["bairro"]     = $jsonCEPs->bairro;
			$resposta["cidade"]     = $jsonCEPs->cidade;
			$resposta["logradouro"] = $jsonCEPs->logradouro;
			$resposta["cep"]        = $jsonCEPs->cep;
			$resposta["estado"]     = $jsonCEPs->estado;

			$return = DaoCEP::InserirCep($resposta);
			//vamos armazenar no banco de dados
			echo self::respotaJSON($resposta);
		} else {
			$ceps = DaoCEP::buscarCepPorCOD(self::setCep());
			$jsonCEPs = $ceps;
			$resposta = [];

			$resposta["bairro"]     = $jsonCEPs["bairro"];
			$resposta["cidade"]     = $jsonCEPs["cidade"];
			$resposta["logradouro"] = $jsonCEPs["logradouro"];
			$resposta["cep"]        = $jsonCEPs["cep"];
			$resposta["estado"]     = $jsonCEPs["estado"];
			//vamos armazenar no banco de dados
			echo self::respotaJSON($resposta);
		}
	}
}