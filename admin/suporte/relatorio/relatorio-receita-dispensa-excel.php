<?php

ob_start();
session_start();

require_once('../../../config/crud.php');
require_once('../../../config/funcoes.php');

$data1 = $_SESSION['inicio'];
$data2 = $_SESSION['fim'];

$valor_total = soma('receber',"WHERE dispensa_data>='$data1' AND dispensa_data<='$data2' AND dispensa='1'",'valor');
$total = conta('receber',"WHERE dispensa_data>='$data1' AND dispensa_data<='$data2' AND dispensa='1'");
$leitura = read('receber',"WHERE dispensa_data>='$data1' AND dispensa_data<='$data2' AND dispensa='1' ORDER BY dispensa_data ASC");
 

$nome_arquivo = "relatorio-dispensa-autorizado";
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
	$html .= "<td>Contrato/Controle</td>";
	$html .= "<td>Nome</td>";
	$html .= "<td>Valor</td>";
	$html .= "<td>Vencimento</td>";

	$html .= "<td>Data Dispensa</td>";

	$html .= "<td>Motivo</td>";

	$html .= "<td>Banco/Pag</td>";
	$html .= "<td>Nota</td>";
$html .= "</tr>";
foreach($leitura as $mostra):

	$contratoId = $mostra['id_contrato'];
	$contrato = mostra('contrato',"WHERE id ='$contratoId'");

	 
		$html .= "<tr>";

			$html .= "<td>".$mostra['id']."</td>";

			$contratoId = $mostra['id_contrato'];
			$contrato = mostra('contrato',"WHERE id ='$contratoId '");

			$html .= "<td>".$contrato['id'].'|'.$contrato['controle']."</td>";

			$clienteId = $mostra['id_cliente'];
			$cliente = mostra('cliente',"WHERE id ='$clienteId '");
			$html .= "<td>".$cliente['nome']."</td>";

		 	$html .= "<td>".converteValor($mostra['valor'])."</td>";

			$html .= "<td>".converteData($mostra['vencimento'])."</td>";
			$html .= "<td>".converteData($mostra['dispensa_data'])."</td>";

			$motivoId=$mostra['dispensa_motivo'];
			$motivo = mostra('motivo_dispensa',"WHERE id ='$motivoId'");
			$html .= "<td>".$motivo['nome']."</td>";
	  	
			$bancoId=$mostra['banco'];
			$banco = mostra('banco',"WHERE id ='$bancoId'");
			$formpagId=$mostra['formpag'];
			$formapag = mostra('formpag',"WHERE id ='$formpagId'");

			$html .= "<td>".$banco['nome']. "|".$formapag['nome']."</td>";
			$html .= "<td>".$mostra['nota']."</td>";
		 
		 
		$html .= "</tr>";
	 

endforeach;


echo $html;

?>