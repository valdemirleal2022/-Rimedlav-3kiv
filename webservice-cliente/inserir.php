<?php

	require_once('../config/crud.php');
	require_once('../config/funcoes.php');
	

	$retorno = array();

 	$cad['nome']  = $_POST["nome"];
 	$cad['email'] = $_POST["email"];

	
 	if(in_array('',$cad)){
		$retorno['retorno'] = "NO";
	 }else{
		create('usuarios',$cad);	
		$retorno['retorno'] = "YES";
	 }
	
	echo json_encode($retorno);

 
	
	//$nome = $_GET["nome"];
//	$email = $_GET["email"];
//
//	$conn = new mysqli("localhost", "wwwpcs_wpc", "!@H087780r", "wwwpcs_wpc_sistema");
//	$sql = "INSERT INTO usuarios (nome,email) VALUES (?, ?)";
//	$stm = $conn->prepare($sql);
//	$stm->bind_param("ss", $nome, $email);
//
//	if ($stm->execute()){
//		$retorno = array("retorno" => "YES");
//	} else {
//		$retorno = array("retorno" => "NO");
//	}
//
//	echo json_encode($retorno);
//
//	$stm->close();
//	$conn->close();


	 
?>