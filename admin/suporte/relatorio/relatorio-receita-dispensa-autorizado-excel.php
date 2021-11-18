<?php

ob_start();
session_start();

require_once('../../../config/crud.php');
require_once('../../../config/funcoes.php');

$data1 = $_SESSION['inicio'];
$data2 = $_SESSION['fim'];
$contratoTipo = $_SESSION['contrato_tipo'];

$leitura = read('receber',"WHERE dispensa='1' AND dispensa_autorizacao='1' AND dispensa_data>='$data1' AND dispensa_data<='$data2' ORDER BY dispensa_data ASC");


	if (!empty($contratoTipo)) {
		$leitura = read('receber',"WHERE dispensa='1' AND dispensa_autorizacao='1' AND dispensa_data>='$data1' AND dispensa_data<='$data2' AND contrato_tipo='$contratoTipo'
		ORDER BY dispensa_data ASC");
	}

if(!empty($statusId )){
	$valor_total = 0;
	$total = 0;
	$status = mostra('contrato_status',"WHERE id='$statusId'");
	
}

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
	$html .= "<td>Controle</td>";
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

			$html .= "<td>".$contrato['controle']."</td>";

			$clienteId = $mostra['id_cliente'];
			$cliente = mostra('cliente',"WHERE id ='$clienteId '");
			$html .= "<td>".$cliente['nome']."</td>";

		 	$html .= "<td>".converteValor($mostra['valor'])."</td>";

			$html .= "<td>".converteData($mostra['vencimento'])."</td>";
			$html .= "<td>".converteData($mostra['dispensa_data'])."</td>";

			if($edit['dispensa_motivo'] == '1'){
				$pdf->Cell(10, 5,'Cobrança Indevida');
				$html .= "<td>Cobrança Indevida</td>";

			}else{
				$html .= "<td>Por Duplicidade</td>";
			 
			}

	  	
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