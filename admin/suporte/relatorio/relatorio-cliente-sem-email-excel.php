<?php

ob_start();
session_start();

require_once('../../../config/crud.php');
require_once('../../../config/funcoes.php');

$total=0;
$leitura = read('cliente',"WHERE id ORDER BY email ASC, email_financeiro ASC");

$nome_arquivo = "relatorio-cliente-sem-email";
header("Content-type: application/vnd.ms-excel");
header("Content-type: application/force-download");
header("Content-Disposition: attachment; filename=$nome_arquivo.xls");
header("Pragma: no-cache");
 
$html = '';
$html .= "<body>";
$html .= "<table>";
$html .= "<tbody>";
$html .= "<tr>";
	$html .= "<td>Nome</td>";
	$html .= "<td>Endereco</td>";
	$html .= "<td>Nome</td>";
	$html .= "<td>Bairro</td>";
	$html .= "<td>Telefone</td>";
	$html .= "<td>Email|Email Financeiro</td>";
	$html .= "<td>Contrato</td>";
	$html .= "<td>Consultor</td>";
	$html .= "<td>Status</td>";
$html .= "</tr>";
foreach($leitura as $mostra):

	$listar='SIM';
	$emailTeste=substr($mostra['email'],0,2);

	if(is_numeric($emailTeste)){
         $listar='SIM';
	}else{
		$listar='NAO';
	}
	if(empty($mostra['email'])){
        $listar='SIM';
	}
	
	if($listar=='SIM'){
		$total++;
		
		$html .= "<tr>";
			 
			$html .= "<td>".$mostra['nome']."</td>";
			$endereco=substr($mostra['endereco'],0,50).','.$mostra['numero'].' - '.$mostra['complemento'];
			$html .= "<td>".$endereco."</td>";	
			$html .= "<td>".$mostra['bairro']."</td>";
			$html .= "<td>".$mostra['telefone']."</td>";
			$html .= "<td>". $mostra['email'].'|'.$mostra['email_financeiro']."</td>";
		
			$clienteId = $mostra['id'];
			$contrato = mostra('contrato',"WHERE id_cliente ='$clienteId'");
		
			$html .= "<td>".substr($contrato['controle'],0,6)."</td>";
		
			$consultorId=$contrato['consultor'];
			$consultor = mostra('contrato_consultor',"WHERE id ='$consultorId'");
			$html .= "<td>".$consultor['nome']."</td>";
		
			$statusId=$contrato['status'];
			$status = mostra('contrato_status',"WHERE id ='$statusId'");
			$html .= "<td>".$status['nome']."</td>";
	
		$html .= "</tr>";
	}
endforeach;


$html .= "<tr>";
	$html .= "<td>Total</td>";
	$html .= "<td>". $total."</td>";
$html .= "</tr>";


echo $html;

?>