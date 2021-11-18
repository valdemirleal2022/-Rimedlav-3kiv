<?php

ob_start();
session_start();

require_once('../../../config/crud.php');
require_once('../../../config/funcoes.php');

$data1 = $_SESSION['inicio'];
$data2 = $_SESSION['fim'];

$total = conta('contrato_baixa',"WHERE id AND falta_pagamento='1' AND data>='$data1' 
												AND data<='$data2'");
$leitura = read('contrato_baixa',"WHERE id AND falta_pagamento='1' AND data>='$data1' 
											  AND data<='$data2' ORDER BY data ASC");

$nome_arquivo = "contratos-suspensos-falta-pagamento";
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
	$html .= "<td>Data</td>";
	$html .= "<td>Motivo</td>";
	$html .= "<td>Status</td>";
	$html .= "<td>Vencimento</td>";
	$html .= "<td>Pagamento</td>";
$html .= "</tr>";

foreach($leitura as $mostra):
	$html .= "<tr>";
		$html .= "<td>".$mostra['id']."</td>";

		$contratoId = $mostra['id_contrato'];
		$contrato = mostra('contrato',"WHERE id = '$contratoId'");

		$clienteId = $mostra['id_cliente'];
		$cliente = mostra('cliente',"WHERE id ='$clienteId '");
		$html .= "<td>".$cliente['nome']."</td>";

		$html .= "<td>".$cliente['bairro']."</td>";

		$consultorId=$contrato['consultor'];
		$consultor = mostra('contrato_consultor',"WHERE id ='$consultorId'");
		$html .= "<td>".$consultor['nome']."</td>";

		$tipoColeta = mostra( 'contrato_coleta', "WHERE id AND id_contrato='$contratoId' ORDER BY vencimento DESC" );
		$html .= "<td>".converteValor($tipoColeta['valor_mensal'])."</td>";

		$html .= "<td>".converteData($mostra['data'])."</td>";

		$html .= "<td>". $mostra['motivo'] ."</td>";
			
		$statusId=$contrato['status'];
		$status = mostra('contrato_status',"WHERE id ='$statusId'");
 		$html .= "<td>". $status['nome'] ."</td>";

	
		$dataSuspensao = $mostra['data'];
		$receber = mostra( 'receber', "WHERE id_contrato='$contratoId' AND vencimento<'$dataSuspensao' ORDER BY vencimento ASC" );
		$html .= "<td>". converteData($receber['vencimento'])."</td>";
		$html .= "<td>". converteData($receber['pagamento']) ."</td>";
 

	$html .= "</tr>";
endforeach;

echo $html;

?>