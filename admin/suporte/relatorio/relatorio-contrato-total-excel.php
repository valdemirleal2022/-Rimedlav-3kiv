<?php

ob_start();
session_start();

require_once('../../../config/crud.php');
require_once('../../../config/funcoes.php');
		
$leitura = read('contrato',"WHERE id AND tipo='2' ORDER BY inicio ASC");

$nome_arquivo = "contratos-total";
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
	$html .= "<td>Tipo</td>";
	$html .= "<td>Classificação</td>";
	$html .= "<td>status</td>";
 
	$html .= "<td>Data de Inicio</td>";
	$html .= "<td>Data do Cancelado</td>";

	$html .= "<td>Meses</td>";

	$html .= "<td>Data Ultimo Faturamento</td>";
	$html .= "<td>Valor Ultimo Faturamento</td>";

$html .= "</tr>";

foreach($leitura as $mostra):

	$html .= "<tr>";

		$html .= "<td>".$mostra['id']."</td>";

		$html .= "<td>".substr($mostra['controle'],0,6)."</td>";

		$clienteId = $mostra['id_cliente'];
		$cliente = mostra('cliente',"WHERE id ='$clienteId '");
		$html .= "<td>".$cliente['nome']."</td>";

		$tipoContratoId=$mostra['contrato_tipo'];
		$tipoContrato= mostra('contrato_tipo',"WHERE id ='$tipoContratoId'");
		$html .= "<td>".$tipoContrato['nome']."</td>";

	 	$classificacaoId = $cliente['classificacao'];
		$classificacao = mostra('cliente_classificacao',"WHERE id ='$classificacaoId'");
		$html .= "<td>".$classificacao['nome']."</td>";

		$statusId = $mostra['status'];
 
		$status = mostra('contrato_status',"WHERE id ='$statusId'");
		$html .= "<td>".$status['nome']."</td>";
		
		$html .= "<td>".converteData($mostra['inicio'])."</td>";
		$html .= "<td>".converteData($mostra['data_cancelamento'])."</td>";

		$date1 = $mostra['inicio'];
 		$date2 = date('Y/m/d');

		if( $mostra['status']=='9'){
			$date2 = $mostra['data_cancelamento'];
			
		}
 
		$diferenca = strtotime($date2) - strtotime($date1);
		//Calcula a diferença em dias
		$dias = floor($diferenca / (60 * 60 * 24));
		$mes = $dias/30;
		$mes = floor($mes);

		$html .= "<td>".$mes."</td>";

		$contratoId = $mostra['id'];

		$receber = mostra('receber',"WHERE id_contrato ='$contratoId' ORDER BY vencimento ASC");

		$html .= "<td>". converteData($receber['emissao'])."</td>";

		$html .= "<td>". converteValor($receber['valor'])."</td>";
 
	$html .= "</tr>";

endforeach;

echo $html;

?>