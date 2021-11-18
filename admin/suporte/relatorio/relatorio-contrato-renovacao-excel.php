<?php

ob_start();
session_start();

require_once('../../../config/crud.php');
require_once('../../../config/funcoes.php');

$data1 = $_SESSION['inicio'];
$data2 = $_SESSION['fim'];

$valor_total = soma('contrato_coleta',"WHERE id AND vencimento>='$data1' AND
																vencimento<='$data2'",'valor_mensal');
$total = conta('contrato_coleta',"WHERE id AND vencimento>='$data1' 
																AND vencimento<='$data2'");
$leitura = read('contrato_coleta',"WHERE id AND vencimento>='$data1'
																AND vencimento<='$data2' ORDER BY vencimento ASC");

$nome_arquivo = "contrato-renovacao";
header("Content-type: application/vnd.ms-excel");
header("Content-type: application/force-download");
header("Content-Disposition: attachment; filename=$nome_arquivo.xls");
header("Pragma: no-cache");
 
$html = '';
$html .= "<body>";
$html .= "<table>";
$html .= "<tbody>";
$html .= "<tr>";
	$html .= "<td>Id|Controle</td>";
	$html .= "<td>Nome</td>";
	$html .= "<td>Coleta</td>";

	$html .= "<td>Valor Unitario</td>";
	$html .= "<td>Valor Extra</td>";
	$html .= "<td>Mensal</td>";

	$html .= "<td>Percentual</td>";

	$html .= "<td>Valor Unitario</td>";
	$html .= "<td>Valor Extra</td>";
	$html .= "<td>Mensal</td>";

	$html .= "<td>Status</td>";
	$html .= "<td>Ultimo Faturamento</td>";
$html .= "</tr>";
foreach($leitura as $mostra):
	$html .= "<tr>";

		$contratoId=$mostra['id_contrato'];
		$contrato = mostra('contrato',"WHERE id ='$contratoId'");
		
		$clienteId = $mostra['id_cliente'];

		$html .= "<td>".$contrato['id'].'|'.substr($contrato['controle'],0,6)."</td>";

		$cliente = mostra('cliente',"WHERE id ='$clienteId '");

		$html .= "<td>".$cliente['nome']."</td>";

		$tipoColetaId = $mostra['tipo_coleta'];
    	$coleta = mostra('contrato_tipo_coleta',"WHERE id ='$tipoColetaId'");
		$html .= "<td>".$coleta['nome']."</td>";

		$html .= "<td>".converteValor($mostra['valor_unitario'])."</td>";
		$html .= "<td>".converteValor($mostra['valor_extra'])."</td>";
		$html .= "<td>".converteValor($mostra['valor_mensal'])."</td>";

		$vencimento=$mostra['vencimento'];
		$coletaRenovacao = mostra('contrato_coleta',"WHERE id AND id_contrato='$contratoId'												AND vencimento>'$vencimento'");
		if($coletaRenovacao){
			$html .= "<td>".converteValor($coletaRenovacao['percentual'])."</td>";
			$html .= "<td>".converteValor($coletaRenovacao['valor_unitario'])."</td>";
			$html .= "<td>".converteValor($coletaRenovacao['valor_extra'])."</td>";
			$html .= "<td>".converteValor($coletaRenovacao['valor_mensal'])."</td>";
		}else{
			$html .= "<td>-</td>";
			$html .= "<td>-</td>";
			$html .= "<td>-</td>";
			$html .= "<td>-</td>";
		}

		$statusId=$contrato['status'];
		$status = mostra('contrato_status',"WHERE id ='$statusId'");
		$html .= "<td>".$status['nome']."</td>";
 
		$receber = mostra( 'receber', "WHERE id_contrato='$contratoId'  ORDER BY vencimento ASC" );

		$html .= "<td>".converteValor($receber['valor'])."</td>";

	$html .= "</tr>";
endforeach;

echo $html;

?>