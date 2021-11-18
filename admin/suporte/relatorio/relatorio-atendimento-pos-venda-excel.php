<?php

ob_start();
session_start();

require_once('../../../config/crud.php');
require_once('../../../config/funcoes.php');

	$data1 = $_SESSION['inicio'];
	$data2 = $_SESSION['fim'];
	$suporte = $_SESSION['suporte'];
	$status = $_SESSION['status'];

			$total = conta('contrato_atendimento_pos_venda',"WHERE id AND data_solicitacao>='$data1' AND data_solicitacao<='$data2' ORDER BY data_solicitacao ASC");
		$leitura = read('contrato_atendimento_pos_venda',"WHERE id AND data_solicitacao>='$data1' AND data_solicitacao<='$data2' ORDER BY data_solicitacao ASC");
	 

	if(!empty($status)){
		$total = conta('contrato_atendimento_pos_venda',"WHERE id AND data_solicitacao>='$data1' AND data_solicitacao<='$data2' AND status='$status'");
		$leitura = read('contrato_atendimento_pos_venda',"WHERE id AND data_solicitacao>='$data1' AND data_solicitacao<='$data2' AND status='$status' ORDER BY data_solicitacao ASC");
	}
		

	if(!empty($suporte)){
		$total = conta('contrato_atendimento_pos_venda',"WHERE id AND data_solicitacao>='$data1' AND data_solicitacao<='$data2' AND id_suporte='$suporte'");
		$leitura = read('pedido',"WHERE id AND data_solicitacao>='$data1' AND data_solicitacao<='$data2' AND id_suporte='$suporte' ORDER BY data_solicitacao ASC");
	}
		
	if(!empty($status) && !empty($suporte ) ){
		$total = conta('contrato_atendimento_pos_venda',"WHERE id AND data_solicitacao>='$data1' AND data_solicitacao<='$data2' 
			AND status='$status' AND id_suporte='$suporte'");
		$leitura = read('contrato_atendimento_pos_venda',"WHERE id AND data_solicitacao>='$data1' AND data_solicitacao<='$data2'
			AND status='$status' AND id_suporte='$suporte' ORDER BY data_solicitacao ASC");
	}

$nome_arquivo = "relatorio-atendimento";
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
	$html .= "<td> Tipo </td>";
	$html .= "<td> Data </td>";

	$html .= "<td> Pos-Venda</td>";

	$html .= "<td> Solicitação </td>";
	$html .= "<td> Motivo </td>";
	$html .= "<td> Status </td>";
$html .= "</tr>";
foreach($leitura as $mostra):

	 
		$html .= "<tr>";
		
			$html .= "<td>".$mostra['id']."</td>";

			$clienteId = $mostra['id_cliente'];
			$contratoId = $mostra['id_contrato'];
			$cliente = mostra('cliente',"WHERE id ='$clienteId '");

			$html .= "<td>".$cliente['nome']."</td>";

			$contrato = mostra('contrato',"WHERE id ='$contratoId'");
			$contratoTipoId = $contrato['contrato_tipo'];
			$contratoTipo = mostra('contrato_tipo',"WHERE id ='$contratoTipoId'");

			$html .= "<td>". $contratoTipo['nome']."</td>";

			$html .= "<td>". converteData($mostra['data_solicitacao'])."</td>";

			$pos_vendaId = $mostra['pos_venda'];
			$posVenda = mostra('contrato_pos_venda',"WHERE id ='$pos_vendaId'");

			$html .= "<td>".$posVenda['nome']."</td>";

			$html .= "<td>". $mostra['solicitacao']."</td>";

			
			
		 	$html .= "<td>".$suporteMotivo['nome']."</td>";
 	
			$html .= "<td>".$mostra['status']."</td>";

			
		 
		 
			$total++;
		
		$html .= "</tr>";
 
endforeach;


echo $html;

?>