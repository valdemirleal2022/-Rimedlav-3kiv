<?php

ob_start();
session_start();

require_once('../../../config/crud.php');
require_once('../../../config/funcoes.php');

$data1 = $_SESSION['inicio'];
$data2 = $_SESSION['fim'];

$valor_total = soma('receber',"WHERE juridico_data>='$data1' AND juridico_data<='$data2' AND juridico='1'",'valor');
$total = conta('receber',"WHERE juridico_data>='$data1' AND juridico_data<='$data2' AND juridico='1'");
$leitura = read('receber',"WHERE juridico_data>='$data1' AND juridico_data<='$data2' AND juridico='1' ORDER BY juridico_data ASC");

$nome_arquivo = "relatorio-juridico";
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
	$html .= "<td>Valor</td>";
	$html .= "<td>Vencimento</td>";
	$html .= "<td>Data Juridico</td>";
	$html .= "<td>Nota</td>";
	$html .= "<td>FormPag/Banco</td>";
	$html .= "<td>Status</td>";
	
$html .= "</tr>";
foreach($leitura as $mostra):

	$contratoId = $mostra['id_contrato'];
	$contrato = mostra('contrato',"WHERE id ='$contratoId'");

	
		$html .= "<tr>";

			$contratoId = $mostra['id_contrato'];
			$contrato = mostra('contrato',"WHERE id ='$contratoId '");

			$html .= "<td>".$mostra['id'].'-'.$contrato['controle']."</td>";

			$clienteId = $mostra['id_cliente'];
			$cliente = mostra('cliente',"WHERE id ='$clienteId '");
			$html .= "<td>".$cliente['nome']."</td>";

			$html .= "<td>". converteValor($mostra['valor']) ."</td>";

			$html .= "<td>". converteData($mostra['vencimento']) ."</td>";

			$html .= "<td>". converteData($mostra['juridico_data']) ."</td>";

			$html .= "<td>".$mostra['nota']."</td>";

			$bancoId=$mostra['banco'];
			$banco = mostra('banco',"WHERE id ='$bancoId'");
			$formpagId=$mostra['formpag'];
			$formapag = mostra('formpag',"WHERE id ='$formpagId'");

			$html .= "<td>".$banco['nome']. "|".$formapag['nome']."</td>";

			if($contrato['status']==5){
				$html .= "<td>Ativo</td>";
			}elseif($contrato['status']==6){
				$html .= "<td>Suspenso</td>";
			}elseif($contrato['status']==9){
				$html .= "<td>Cancelado</td>";;
			}else{
				$html .= "<td>Sem Status</td>";;
			}

			 
		$html .= "</tr>";
 
endforeach;


echo $html;

?>