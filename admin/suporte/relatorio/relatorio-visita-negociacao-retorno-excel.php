<?php

ob_start();
session_start();

require_once('../../../config/crud.php');
require_once('../../../config/funcoes.php');

$data1 = $_SESSION['inicio'];
$data2 = $_SESSION['fim'];
 
$total = conta('cadastro_visita_negociacao',"WHERE id AND status='1' AND retorno>='$data1' AND retorno<='$data2' ORDER BY retorno ASC");
$leitura = read('cadastro_visita_negociacao',"WHERE id AND status='1' AND retorno>='$data1' AND retorno<='$data2' ORDER BY retorno ASC");

$nome_arquivo = "relatorio-visita-negociacao-retorno";
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
	$html .= "<td>Consultor</td>";
	$html .= "<td>Descrição</td>";
	$html .= "<td>Interação</td>";
	$html .= "<td>Retorno</td>";
$html .= "</tr>";
foreach($leitura as $mostra):

		$html .= "<tr>";
			$html .= "<td>".$mostra['id']."</td>";

			$visitaId = $mostra['id_visita'];
			$visita = mostra('cadastro_visita',"WHERE id ='$visitaId'");
		 
			$html .= "<td>".$visita['nome']."</td>";
			$consultorId = $mostra['consultor'];
			$consultor = mostra('contrato_consultor',"WHERE id ='$consultorId '");
			$html .= "<td>".$consultor['nome']."</td>";	
			$html .= "<td>".$mostra['descricao']."</td>";	
			$html .= "<td>".date('d/m/Y H:i:s',strtotime($mostra['interacao']))."</td>";	
			$html .= "<td>".date('d/m/Y',strtotime($mostra['retorno']))."</td>";
	 
		$html .= "</tr>";
	
endforeach;


echo $html;

?>