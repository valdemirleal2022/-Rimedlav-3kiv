<?php

ob_start();
session_start();

require_once('../../../config/crud.php');
require_once('../../../config/funcoes.php');



$total = conta('contrato',"WHERE id AND cobrar_locacao='1'");
$leitura = read('contrato',"WHERE id AND cobrar_locacao='1' ORDER BY inicio DESC");


$nome_arquivo = "contrato-locacao";
header("Content-type: application/vnd.ms-excel");
header("Content-type: application/force-download");
header("Content-Disposition: attachment; filename=$nome_arquivo.xls");
header("Pragma: no-cache");
 
$html = '';
$html .= "<body>";
$html .= "<table>";
$html .= "<tbody>";
$html .= "<tr>";

	$html .= "<td>Contrato</td>";

	$html .= "<td>Nome</td>";
	$html .= "<td>Bairro</td>";
	$html .= "<td>Consultor</td>";

	$html .= "<td>Tipo de Coleta</td>";
	$html .= "<td>Quantidade</td>";
	
	
	$html .= "<td>Valor Unitario</td>";
	$html .= "<td>Valor Mensal</td>";
 
	$html .= "<td>Tipo de Contrato</td>";

$html .= "</tr>";
foreach($leitura as $mostra):
	$html .= "<tr>";

		
		$html .= "<td>".$mostra['id'].'|'.substr($mostra['controle'],0,6)."</td>";

		$clienteId = $mostra['id_cliente'];
		$cliente = mostra('cliente',"WHERE id ='$clienteId '");

		$html .= "<td>".$cliente['nome']."</td>";
		$html .= "<td>".$cliente['bairro']."</td>";

		$consultorId=$mostra['consultor'];
		$consultor = mostra('contrato_consultor',"WHERE id ='$consultorId'");
		$html .= "<td>".$consultor['nome']."</td>";

		$contratoId = $mostra['id'];

		$contratoColeta = mostra('contrato_coleta',"WHERE id_contrato ='$contratoId'");
		$tipoColetaId = $contratoColeta['tipo_coleta'];
    	$coleta = mostra('contrato_tipo_coleta',"WHERE id ='$tipoColetaId'");

		$html .= "<td>".$coleta['nome']."</td>";
		$html .= "<td>".$contratoColeta['quantidade']."</td>";
		$html .= "<td>".converteValor($contratoColeta['valor_unitario'])."</td>";

		$html .= "<td>".converteValor($mostra['valor_mensal'])."</td>";

		$contratoTipoId = $mostra['contrato_tipo'];
		$contratoTipo = mostra('contrato_tipo',"WHERE id ='$contratoTipoId'");
		$html .= "<td>".$contratoTipo['nome']."</td>";
		

	$html .= "</tr>";
endforeach;

echo $html;

?>