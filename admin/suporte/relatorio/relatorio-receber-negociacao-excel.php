<?php

ob_start();
session_start();

require_once('../../../config/crud.php');
require_once('../../../config/funcoes.php');

$data1 = $_SESSION['inicio'];
$data2 = $_SESSION['fim'];
$motivo = $_SESSION['motivo'];
$usuarioPesquisa = $_SESSION['usuarioPesquisa'];

$total = conta('receber_negociacao',"WHERE id AND data>='$data1' AND data<='$data2' ORDER BY data ASC");
$leitura = read('receber_negociacao',"WHERE id AND data>='$data1' AND data<='$data2' ORDER BY data ASC, id_cliente ASC, id_usuario ASC, peso DESC");

if(!empty($usuarioPesquisa)){
		$total = conta('receber_negociacao',"WHERE id AND data>='$data1' AND data<='$data2' AND id_usuario='$usuarioPesquisa'");
		$leitura = read('receber_negociacao',"WHERE id AND data>='$data1' AND data<='$data2' AND id_usuario='$usuarioPesquisa' ORDER BY data ASC");
}

if(!empty($motivo)){
		$total = conta('receber_negociacao',"WHERE id AND data>='$data1' AND data<='$data2' AND id_motivo='$motivo'");
		$leitura = read('receber_negociacao',"WHERE id AND data>='$data1' AND data<='$data2' AND id_motivo='$motivo' ORDER BY data ASC");
}

$nome_arquivo = "relatorio-receber-negociacao";
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
	$html .= "<td> Valor  </td>";
	$html .= "<td> Vencimento </td>";
	$html .= "<td> Status </td>";
	$html .= "<td> Data </td>";
	$html .= "<td> Hora </td>";
	$html .= "<td> Previsao Pag </td>";
	$html .= "<td> Motivo </td>";
	$html .= "<td> Solucao </td>";
	$html .= "<td> Peso </td>";
	$html .= "<td> Usuario </td>";
 
$html .= "</tr>";
foreach($leitura as $mostra):

	 
		$html .= "<tr>";
		
			$html .= "<td>".$mostra['id']."</td>";

			$clienteId = $mostra['id_cliente'];
			$receberId = $mostra['id_receber'];
			$cliente = mostra('cliente',"WHERE id ='$clienteId '");

			$html .= "<td>".$cliente['nome']."</td>";

			$receber = mostra('receber',"WHERE id ='$receberId'");

			$html .= "<td>".converteValor($receber['valor'])."</td>";
			$html .= "<td>".converteData($receber['vencimento'])."</td>";

			$html .= "<td>".$receber['status']."</td>";
 
			$html .= "<td>".converteData($mostra['data'])."</td>";
			$html .= "<td>".$mostra['hora']."</td>";

 			$html .= "<td>".converteData($mostra['previsao_pagamento'])."</td>";

			$motivoId = $mostra['id_motivo'];
			$motivo = mostra('recebe_negociacao_motivo',"WHERE id ='$motivoId'");
			$html .= "<td>".$motivo['nome']."</td>";

			$solucaoId = $mostra['id_solucao'];
			$solucao = mostra('recebe_negociacao_solucao',"WHERE id ='$solucaoId'");
			$html .= "<td>".$solucao['nome']."</td>";
			$html .= "<td>".$motivo['peso']."</td>";
 
			$usuarioId = $mostra['id_usuario'];
			$usuarioMostra = mostra('usuarios',"WHERE id ='$usuarioId '");
			$html .= "<td>". $usuarioMostra['nome']."</td>";

			
		 
		 
			$total++;
		
		$html .= "</tr>";
 
endforeach;


echo $html;

?>