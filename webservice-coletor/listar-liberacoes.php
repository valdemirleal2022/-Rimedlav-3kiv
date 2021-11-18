<?php

require_once('../config/crud.php');
require_once('../config/funcoes.php');

$retorno = array();

$rotaId = $_POST["rotaId"];
$dataRota = $_POST["dataRota"];
$dataRota = implode("-",array_reverse(explode("/",$dataRota)));

//$dataRota = '31/12/2019';
//$dataRota = date('Y-m-a', strtotime($dataRota));
//echo $dataRota."<br>";  
//$dataRota = implode("-",array_reverse(explode("/",$dataRota)));
 
//$dataRota = date( "Y/m/d");
//$rotaId= '2';
 
$leitura = read('veiculo_liberacao',"WHERE id AND saida='$dataRota' AND rota ='$rotaId'"); 
 
if($leitura){
   foreach($leitura as $mostra):

		$cad["liberacaoId"] = $mostra['id'];
		$cad["hora"] = $mostra['saida_hora'];
	 
		$veiculoId = $mostra['id_veiculo'];
		$veiculo= mostra('veiculo',"WHERE id ='$veiculoId'");
		$cad["veiculo"] = tirarAcentos($veiculo['placa']);
	
		$motoristaId = $mostra['motorista'];
		$motorista= mostra('veiculo_motorista',"WHERE id ='$motoristaId'");
		$cad["motorista"] = tirarAcentos($motorista['nome']);
	
		$motoristaId = $mostra['ajudante1'];
		$motorista= mostra('veiculo_motorista',"WHERE id ='$motoristaId'");
		$cad["ajudante1"] = tirarAcentos($motorista['nome']);
	
		$motoristaId = $mostra['ajudante2'];
		$motorista= mostra('veiculo_motorista',"WHERE id ='$motoristaId'");
		$cad["ajudante2"] = tirarAcentos($motorista['nome']);
	
		$aterroId = $mostra['aterro'];
		$aterro= mostra('aterro',"WHERE id ='$aterroId'");
		$cad["aterro"] = tirarAcentos($aterro['nome']);


		if(empty($cad["liberacaoId"])){
			$cad["liberacaoId"] ='-';
		}
		if(empty($cad["hora"])){
			$cad["hora"] ='-';
		}
		if(empty($cad["veiculo"])){
			$cad["veiculo"] ='-';
		}
		if(empty($cad["motorista"])){
			$cad["motorista"] ='-';
		}
		if(empty($cad["ajudante1"])){
			$cad["ajudante1"] ='-';
		}
		if(empty($cad["ajudante2"])){
			$cad["ajudante2"] ='-';
		}
		if(empty($cad["aterro"])){
			$cad["aterro"] ='-';
		}
	
		if($mostra['checklist_motorista']=='1'){
			
			$cad["liberacaoId"] ='';
			$cad["hora"] ='Já Baixado';
			$cad["veiculo"] ='';
			$cad["motorista"] ='';
			$cad["ajudante1"] ='';
			$cad["ajudante2"] ='';
			$cad["aterro"] ='';
			
		}
	

	 	$retorno[] = $cad;
	 
   endforeach;
	
}else{
 
		$cad["liberacaoId"] ='';
		$cad["hora"] ='Sem Liberacao';
		$cad["veiculo"] ='';
		$cad["motorista"] ='';
		$cad["ajudante1"] ='';
		$cad["ajudante2"] ='';;
		$cad["aterro"] ='';
		$retorno[] = $cad;
	
}

	
echo json_encode($retorno);

 
?>