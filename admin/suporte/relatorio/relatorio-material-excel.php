<?php

ob_start();
session_start();

require_once('../../../config/crud.php');
require_once('../../../config/funcoes.php');

$total = conta('estoque_material',"WHERE id");
$leitura = read('estoque_material',"WHERE id ORDER BY codigo ASC");

$nome_arquivo = "relatorio-material";
header("Content-type: application/vnd.ms-excel");
header("Content-type: application/force-download");
header("Content-Disposition: attachment; filename=$nome_arquivo.xls");
header("Pragma: no-cache");
 
$html = '';
$html .= "<body>";
$html .= "<table>";
$html .= "<tbody>";
$html .= "<tr>";
	$html .= "<td>Codigo</td>";
	$html .= "<td>Descrição</td>";
	$html .= "<td>Estoque</td>";
	$html .= "<td>Estoque Min</td>";
	$html .= "<td>Unidade</td>";
	$html .= "<td>Valor Unitario</td>";
	$html .= "<td>Valor Total</td>";
	$html .= "<td>Status</td>";

$html .= "</tr>";

foreach($leitura as $mostra):
	$html .= "<tr>";

		$html .= "<td>".$mostra['codigo']."</td>";
 		$html .= "<td>".$mostra['nome']."</td>";
		$html .= "<td>".$mostra['estoque']."</td>";
 		$html .= "<td>".$mostra['estoque_minimo']."</td>";
		$html .= "<td>".$mostra['unidade']."</td>";

		$valor = $mostra['estoque']*$mostra['valor_unitario'];

 		$html .= "<td>".converteValor($mostra['valor_unitario'])."</td>";
 		$html .= "<td>".converteValor($valor)."</td>";

		if($mostra['status']==1){
         	$html .= "<td>Ativo</td>";
		}else{
			$html .= "<td>Inativo</td>";
     	}

	 	 
	$html .= "</tr>";
endforeach;

echo $html;

?>