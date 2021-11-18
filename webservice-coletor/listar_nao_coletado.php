<?php

require_once('../config/crud.php');
require_once('../config/funcoes.php');

$retorno = array();

$leitura = read('ordem_motivo_naocoletado',"WHERE id ORDER BY id ASC"); 
$contador = 0;
if($leitura){
   foreach($leitura as $mostra):

		$cad["nome"] = tirarAcentos($mostra['nome']);
		
	 	$retorno[] = $cad;
	 
   endforeach;
	
   echo json_encode($retorno);
	
}

 
?>