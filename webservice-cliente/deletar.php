<?php

	require_once('../config/crud.php');
	require_once('../config/funcoes.php');

	$codigo = $_POST["codigo"];

	$retorno = array();
 
	
	if(!empty($codigo)){
		delete('usuarios',"id = '$codigo'");
		$retorno['retorno'] = "YES";
	 }else{
		$retorno['retorno'] = "NO";
	 }
	echo json_encode($retorno);
  
?>