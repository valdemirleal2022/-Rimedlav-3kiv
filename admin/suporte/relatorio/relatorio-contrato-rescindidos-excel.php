<?php

ob_start();
session_start();

require_once('../../../config/crud.php');
require_once('../../../config/funcoes.php');

if(isset($_SESSION['inicio'])){
	$data1 = $_SESSION['inicio'];
}
if(isset($_SESSION['fim'])){
	$data2 = $_SESSION['fim'];
}

$valor_total = soma('contrato',"WHERE id AND tipo='2' AND status='7' AND data_rescisao>='$data1' 
					 AND data_rescisao<='$data2' AND status='7'",'valor_mensal');
$total = conta('contrato',"WHERE id AND tipo='2' AND data_rescisao>='$data1' 
					AND data_rescisao<='$data2' AND status='7'");
$leitura = read('contrato',"WHERE id AND tipo='2' AND data_rescisao>='$data1' 
					AND data_rescisao<='$data2' AND status='7' ORDER BY data_rescisao ASC");

$nome_arquivo = "contratos-rescindidos";
header("Content-type: application/vnd.ms-excel");
header("Content-type: application/force-download");
header("Content-Disposition: attachment; filename=$nome_arquivo.xls");
header("Pragma: no-cache");
 
$html = '';
$html .= "<body>";
$html .= "<table>";
$html .= "<tbody>";
$html .= "<tr>";
	$html .= "<td>Id</td>";
	$html .= "<td>Nome</td>";
	$html .= "<td>Bairro</td>";
	$html .= "<td>Consultor</td>";
	$html .= "<td>Valor Mensal</td>";
	$html .= "<td>A partir</td>";
	$html .= "<td>Motivo</td>";
	$html .= "<td>Encerramento</td>";
$html .= "</tr>";

foreach($leitura as $mostra):
	$html .= "<tr>";
		$html .= "<td>".$mostra['id']."</td>";

		$contratoId = $mostra['id'];
		$clienteId = $mostra['id_cliente'];

		$cliente = mostra('cliente',"WHERE id ='$clienteId '");
		$html .= "<td>".$cliente['nome']."</td>";
		$html .= "<td>".$cliente['bairro']."</td>";

		$consultorId=$mostra['consultor'];
		$consultor = mostra('contrato_consultor',"WHERE id ='$consultorId'");
		$html .= "<td>".$consultor['nome']."</td>";

		$html .= "<td>".converteValor($mostra['valor_mensal'])."</td>";

		$html .= "<td>".converteData($mostra['data_suspensao'])."</td>";

		$contratoBaixa = mostra('contrato_baixa',"WHERE id_contrato ='$contratoId' AND tipo='2' 
							ORDER BY interacao ASC");
		$html .= "<td>". substr($contratoBaixa['motivo'],0,20) ."</td>";
			
		$statusId=$mostra['status'];
		$status = mostra('contrato_status',"WHERE id ='$statusId'");

		$contratoCancelamento = mostra('contrato_cancelamento',"WHERE id_contrato ='$contratoId' ORDER BY interacao ASC");
		$html .= "<td>".converteData($contratoCancelamento['data_encerramento'])."</td>";
			 
		
	$html .= "</tr>";
endforeach;

echo $html;

?>