<?php

require_once('../config/crud.php');
require_once('../config/funcoes.php');

$retorno = array();

$rotaId = $_POST["rotaId"];
$dataRota = $_POST["dataRota"];

//$dataRota = '31/12/2019';
//$dataRota = date('Y-m-a', strtotime($dataRota));
//echo $dataRota."<br>";  
$dataRota = implode("-",array_reverse(explode("/",$dataRota)));
 
 $dataRota = date( "Y/m/d");
 $rotaId= '54';
 
$leitura = read('contrato_ordem',"WHERE id AND data='$dataRota' AND rota='$rotaId' AND status='12' ORDER BY data DESC, hora ASC"); 

$contador = 0;

if($leitura){
	
   foreach($leitura as $mostra):

		$contador = $contador + 1 ;
	
		$cad["contador"] = $contador;
	
		$cad["ordemId"] = $mostra['id'];
		$cad["hora"] = $mostra['hora'];
	
		$cad["contrato"] = $mostra['id_contrato'];
	
		$clienteId = $mostra['id_cliente'];
		$clienteLeitura = read('cliente',"WHERE id ='$clienteId'");
		if($clienteLeitura){
			
			foreach($clienteLeitura as $cliente)
			$cad["nome"] = tirarAcentos($cliente['nome']);
	 
			$cad["nome"] = ucwords(strtolower($cad["nome"])); 
		 
			$cad["nome"] = substr($cad["nome"],0,50); 
		 
			$cad["bairro"] = tirarAcentos($cliente['bairro']);
			$cad["bairro"] = ucwords(strtolower($cad["bairro"])); 
			$cad["bairro"] = trim($cad["bairro"]); 

			$endereco=$cliente['endereco'].', '.$cliente['numero'].' '.$cliente['complemento'];
			$cad["endereco"] = tirarAcentos($endereco);
			$cad["endereco"] = ucwords(strtolower($cad["endereco"]));  
		}
 
		 
		$tipoColetaId = $mostra['tipo_coleta1'];
        $coletaLeitura = read('contrato_tipo_coleta',"WHERE id ='$tipoColetaId'");
	
		if($coletaLeitura){
			foreach($coletaLeitura as $coleta)
				
			$cad["coleta"] = tirarAcentos($coleta['nome']);
			
		}
	 
		if(empty($cad["ordemId"])){
			$cad["ordemId"] ='-';
		}
		if(empty($cad["hora"])){
			$cad["hora"] ='-';
		}
		if(empty($cad["contrato"])){
			$cad["contrato"] ='-';
		}
		if(empty($cad["nome"])){
			$cad["nome"] ='-';
		}
		if(empty($cad["endereco"])){
			$cad["endereco"] ='-';
		}
		if(empty($cad["bairro"])){
			$cad["bairro"] ='-';
		}
		if(empty($cad["coleta"])){
			$cad["coleta"] ='-';
		}
		if(empty($cad["coleta"])){
			$cad["coleta"] ='-';
		}
	 
	 	$retorno[] = $cad;
	 
   endforeach;
 
}else{
	
	
		$cad["contador"] = $contador;
		$cad["ordemId"] ='...';
		$cad["contrato"] ='...';
		$cad["hora"] ='...';
		$cad["nome"] ='Sem Coleta nesta Data';
		$cad["endereco"] ='...';
		$cad["bairro"] ='...';
		$cad["coleta"] ='...';
		$retorno[] = $cad;;
	
}

	
echo json_encode($retorno);

 
?>