<?php

	require_once('../config/crud.php');
	require_once('../config/funcoes.php');

	$retorno = array();
 
 	$email = $_POST["email"];
	$senha = $_POST["senha"];

	$retorno['retorno'] = "NO";

	$leitura = read( 'contrato_rota', "WHERE email = '$email' AND senha = '$senha'" );
	if($leitura){
	   foreach($leitura as $mostra):
	
			$retorno["id"] = $mostra['id'];
			$retorno["nome"] = $mostra['nome'];
			$retorno["email"] = $mostra['email'];
			$retorno["retorno"] = "YES";
			break;

	   endforeach;
		
	}else{
		
		$retorno['retorno'] = "NO";
		
	}
	
	 echo json_encode($retorno);
 
	 
?>