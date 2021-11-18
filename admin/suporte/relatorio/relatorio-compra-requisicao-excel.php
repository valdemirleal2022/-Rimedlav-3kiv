<?php

ob_start();
session_start();

require_once('../../../config/crud.php');
require_once('../../../config/funcoes.php');

$data1 = $_SESSION['inicio'];
$data2 = $_SESSION['fim'];

$total = conta('estoque_material_requisicao',"WHERE id AND data>='$data1' 
											  AND data<='$data2'");
$leitura = read('estoque_material_requisicao',"WHERE id AND data>='$data1' 
											  AND data<='$data2' ORDER BY data ASC");

$nome_arquivo = "relatorio-compra-requisicoes";
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
$html .= "<td>Area</td>";
$html .= "<td>Solicitante</td>";
$html .= "<td>Data</td>";
$html .= "<td>Material</td>";
$html .= "<td>Q Solicitada</td>";
$html .= "<td>Q Liberada</td>";
$html .= "<td>Estoque</td>";
$html .= "<td>Status</td>";

$html .= "</tr>";

foreach($leitura as $mostra):
	$html .= "<tr>";

		$html .= "<td>".$mostra['id']."</td>";
		$html .= "<td>".$mostra['area']."</td>";

		$solicitanteId = $mostra['solicitante'];
		$solicitante = mostra('usuarios',"WHERE id ='$solicitanteId'");
		$html .= "<td>".$solicitante['nome']."</td>";

		$html .= "<td>".converteData($mostra['data'])."</td>";

		$materialId = $mostra['id_material'];
		$estoque = mostra('estoque_material',"WHERE id ='$materialId'");
		$html .= "<td>".$estoque['nome']."</td>";
 
		$html .= "<td>".$mostra['quantidade']."</td>";
		$html .= "<td>".$mostra['quantidade_liberada']."</td>";
		$html .= "<td>".$estoque['estoque']."</td>";
		 
		$html .= "<td>". $mostra['status'] ."</td>";
			
		 
	$html .= "</tr>";
endforeach;

echo $html;

?>