<?php

ob_start();
session_start();

require_once('../../../config/crud.php');
require_once('../../../config/funcoes.php');

	$data1 = $_SESSION['inicio'];
	$data2 = $_SESSION['fim'];
	$suporte = $_SESSION['suporte'];
	$status = $_SESSION['status'];

	$total = conta('ouvidoria',"WHERE id AND data_solicitacao>='$data1' AND data_solicitacao<='$data2' ORDER BY data_solicitacao ASC");
	$leitura = read('ouvidoria',"WHERE id AND data_solicitacao>='$data1' AND data_solicitacao<='$data2' ORDER BY data_solicitacao ASC");


	if(!empty($status)){
		$total = conta('ouvidoria',"WHERE id AND data_solicitacao>='$data1' AND data_solicitacao<='$data2' AND status='$status'");
		$leitura = read('ouvidoria',"WHERE id AND data_solicitacao>='$data1' AND data_solicitacao<='$data2' AND status='$status' ORDER BY data_solicitacao ASC");
	}
 
	if(!empty($suporte)){
		$total = conta('ouvidoria',"WHERE id AND data_solicitacao>='$data1' AND data_solicitacao<='$data2' AND id_suporte='$suporte'");
		$leitura = read('ouvidoria',"WHERE id AND data_solicitacao>='$data1' AND data_solicitacao<='$data2' AND id_suporte='$suporte' ORDER BY data_solicitacao ASC");
	}
		
	if(!empty($status) && !empty($suporte ) ){
		$total = conta('ouvidoria',"WHERE id AND data_solicitacao>='$data1' AND data_solicitacao<='$data2' 
			AND status='$status' AND id_suporte='$suporte'");
		$leitura = read('ouvidoria',"WHERE id AND data_solicitacao>='$data1' AND data_solicitacao<='$data2'
			AND status='$status' AND id_suporte='$suporte' ORDER BY data_solicitacao ASC");
	}
 

$nome_arquivo = "relatorio-ouvidora";
header("Content-type: application/vnd.ms-excel");
header("Content-type: application/force-download");
header("Content-Disposition: attachment; filename=$nome_arquivo.xls");
header("Pragma: no-cache");
 
$html = '';
$html .= "<body>";
$html .= "<table>";
$html .= "<tbody>";
$html .= "<tr>";
	$html .= "<td> Id </td>";
	$html .= "<td> Nome </td>";
	$html .= "<td> Data Solicitação </td>";
	$html .= "<td> hora </td>";
	$html .= "<td> Atendente Abertura </td>";
	$html .= "<td> Motivo </td>";
	$html .= "<td> Solicitacao </td>";
	$html .= "<td> Contato </td>";
	$html .= "<td> Data Solução </td>";
	$html .= "<td> hora </td>";
	$html .= "<td> Atendente Fechamento </td>";
	$html .= "<td> Solucao </td>";
	$html .= "<td> Origem </td>";
	$html .= "<td> Rota </td>";
	$html .= "<td> Status </td>";
$html .= "</tr>";
foreach($leitura as $mostra):

	 
		$html .= "<tr>";
		
			$html .= "<td>".$mostra['id']."</td>";

			$clienteId = $mostra['id_cliente'];
			$cliente = mostra('cliente',"WHERE id ='$clienteId '");

			$html .= "<td>".$cliente['nome']."</td>";


			$html .= "<td>".converteData($mostra['data_solicitacao'])."</td>";
			$html .= "<td>".$mostra['hora_solicitacao']."</td>";

			$html .= "<td>".$mostra['atendente_abertura']."</td>";

			$suporteId=$mostra['id_suporte'];
			$suporte = mostra('pedido_suporte',"WHERE id ='$suporteId'");

			$html .= "<td>".$suporte['nome']."</td>";

 			$html .= "<td>".$mostra['solicitacao']."</td>";
			$html .= "<td>".$mostra['contato']."</td>";


			$html .= "<td>".converteData($mostra['data_solucao'])."</td>";
			$html .= "<td>".$mostra['hora_solucao']."</td>";

			$html .= "<td>".$mostra['atendente_fechamento']."</td>";

			$html .= "<td>".$mostra['solucao']."</td>";

			$origemId = $mostra['id_origem'];
			$origem = mostra('pedido_origem',"WHERE id ='$origemId '");
			$html .= "<td>".$origem['nome']."</td>";

			$rotaId = $mostra['rota'];
			$rota = mostra('contrato_rota',"WHERE id ='$rotaId'");
			$html .= "<td>".$rota['nome']."</td>";
		
			$html .= "<td>".$mostra['status']."</td>";
		 
		 
			$total++;
		
		$html .= "</tr>";
 
endforeach;


echo $html;

?>