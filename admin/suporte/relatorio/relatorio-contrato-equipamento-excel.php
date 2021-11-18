<?php

ob_start();
session_start();

require_once('../../../config/crud.php');
require_once('../../../config/funcoes.php');

$total = conta('estoque_equipamento_retirada',"WHERE id AND status='Baixado' AND tipo<>'3'");
$leitura = read('estoque_equipamento_retirada',"WHERE id AND status='Baixado' AND tipo<>'3' ORDER BY id_contrato ASC");

$nome_arquivo = "relatorio-contrato-equipamento";
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
	$html .= "<td>Cliente</td>";
	$html .= "<td>Tipo Coleta</td>";
	$html .= "<td>Min</td>";
	$html .= "<td>Equipamento</td>";
	$html .= "<td>Quant</td>";
	$html .= "<td>Entrega</td>";
	$html .= "<td>Tipo</td>";
	$html .= "<td>Status</td>";
$html .= "</tr>";

foreach($leitura as $mostra):
	$html .= "<tr>";

		$equipamentoId = $mostra['id_equipamento'];
		$contratoId = $mostra['id_contrato'];
		$clienteId = $mostra['id_cliente'];

		$clienteId = $mostra['id_cliente'];
		$cliente = mostra('cliente',"WHERE id ='$clienteId '");

		$equipamento = mostra('estoque_equipamento',"WHERE id ='$equipamentoId'");

		$html .= "<td>".$contratoId."</td>";
		$html .= "<td>".$cliente['nome']."</td>";

		$contratoColeta = mostra('contrato_coleta',"WHERE id_contrato ='$contratoId'");
		$tipoColetaId = $contratoColeta['tipo_coleta'];
		$coleta = mostra('contrato_tipo_coleta',"WHERE id ='$tipoColetaId'");
		
		$html .= "<td>".$coleta['nome']."</td>";
		$html .= "<td>".$contratoColeta['quantidade']."</td>";

		$html .= "<td>".$equipamento['nome']."</td>";
		$html .= "<td>".$mostra['quantidade']."</td>";

		$html .= "<td>".converteData($mostra['data_entrega'])."</td>";

		if($mostra['tipo'] == '1'){
			$html .= "<td>Troca</td>";
		}elseif($mostra['tipo'] == '2'){
			$html .= "<td>Entrega</td>";
		}elseif($mostra['tipo'] == '3'){
			$html .= "<td>Retirada</td>";
		}else{
			$html .= "<td>-</td>";
		} 

		if($mostra['tipo'] == '1'){
				$status='Troca';
			}elseif($mostra['tipo'] == '2'){
				$status='Entrega';
			}elseif($mostra['tipo'] == '3'){
				$status='Retirada';
			}else{
				$status='-';
		} 

		$html .= "<td>". $status ."</td>";

			
		 
	$html .= "</tr>";
endforeach;

echo $html;

?>