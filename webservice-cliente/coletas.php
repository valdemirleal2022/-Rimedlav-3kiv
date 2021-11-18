<?php

require_once('../config/crud.php');
require_once('../config/funcoes.php');

$retorno = array();

$clienteId = $_POST["clienteId"];
 
 
$leitura = read('contrato_ordem',"WHERE id AND id_cliente='$clienteId' ORDER BY data DESC"); 

$contador = 0;
if($leitura){
   foreach($leitura as $mostra):

		$contador = $contador + 1 ;
	
		$cad["contador"] = $contador;
	
		$cad["ordemId"] = $mostra['id'];
		$cad["data"] = converteData($mostra['data']);

 		$tipoColetaId = $mostra['tipo_coleta1'];
        $coleta = mostra('contrato_tipo_coleta',"WHERE id ='$tipoColetaId'");
				
		$cad["coleta"] = tirarAcentos($coleta['nome']);
		$cad["quantidade"] =  $mostra['quantidade1'];
	
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
		if(empty($cad["quantidade"])){
			$cad["quantidade"] ='-';
		}

	 	$retorno[] = $cad;
	 
   endforeach;
	
}else{
	
	
		$cad["contador"] = $contador;
		$cad["ordemId"] ='...';
		$cad["data"] ='...';
		$cad["coleta"] ='...';
		$cad["quantidade"] ='...';
		$retorno[] = $cad;;
	
}

	
echo json_encode($retorno);

 
?>