
<?php

	require_once('../config/crud.php');
	require_once('../config/funcoes.php');

	$retorno = array();

	$ordemId = $_POST["ordemId"];

	if(!empty($_FILES['assinatura']['tmp_name'])){
		$imagem = $_FILES['assinatura'];
		$pasta  = '../uploads/assinaturas-ordem/';
		$tmp    = $imagem['tmp_name'];
		$ext    = substr($imagem['name'],-3);
		$nome   = md5(time()).'.'.$ext;
		$cad['assinatura'] = $nome;
		uploadImg($tmp, $nome, '350', $pasta);	
	}

 
	//$ordemId='2694317';
	$retorno['retorno'] = "NO";
	
	$readordem = read('contrato_ordem',"WHERE id = '$ordemId'");
	if($readordem){
		foreach($readordem as $edit);

			$retorno['retorno'] = "YES";
			update('contrato_ordem',$cad,"id = '$ordemId'");

	}else{
		$retorno['retorno'] = "NO";		
	}

	echo json_encode($retorno);

 
?>