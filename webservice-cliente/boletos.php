<?php

require_once('../config/crud.php');
require_once('../config/funcoes.php');

$retorno = array();

$clienteId = $_POST["clienteId"];
 
 
$leitura = read('receber',"WHERE id AND id_cliente='$clienteId' ORDER BY vencimento DESC"); 

$contador = 0;
if($leitura){
   foreach($leitura as $mostra):

		$contador = $contador + 1 ;
	
		$cad["contador"] = $contador;
	
		$cad["boletoId"] = $mostra['id'];
		$cad["emissao"] = converteData($mostra['emissao']);
		$cad["vencimento"] = converteData($mostra['vencimento']);
 		$cad["valor"] = converteValor($mostra['valor']);
		$cad["status"] = $mostra['status'];
	
		if(empty($cad["boleto"])){
			$cad["boleto"] ='-';
		}
		if(empty($cad["emissao"])){
			$cad["emissao"] ='-';
		}
		if(empty($cad["vencimento"])){
			$cad["vencimento"] ='-';
		}
		if(empty($cad["valor"])){
			$cad["valor"] ='-';
		}
		if(empty($cad["status"])){
			$cad["status"] ='-';
		}
 
	 	$retorno[] = $cad;
	 
   endforeach;
	
}else{
	
	
		$cad["contador"] = $contador;
		$cad["boletoId"] ='...';
		$cad["emissao"] ='...';
		$cad["vencimento"] ='...';
		$cad["valor"] ='...';
		$cad["status"] ='...';
		$retorno[] = $cad;;
	
}

	
echo json_encode($retorno);

 
?>