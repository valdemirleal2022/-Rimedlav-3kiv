<?php

ob_start();
session_start();

require_once('../../../config/crud.php');
require_once('../../../config/funcoes.php');

$data1 = $_SESSION['inicio'];
$data2 = $_SESSION['fim'];

$leitura = read('contrato_aditivo',"WHERE id AND aprovacao>='$data1' 
											  AND aprovacao<='$data2' ORDER BY aprovacao ASC");
$total = conta('contrato_aditivo',"WHERE id AND aprovacao>='$data1' 
											  AND aprovacao<='$data2' ORDER BY aprovacao ASC");


$nome_arquivo = "contratos-aditivos";
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
	$html .= "<td>Aprovacao</td>";
	$html .= "<td>Inicio</td>";

	$html .= "<td>Endereco</td>";
	$html .= "<td>Endereco Adivivo</td>";

	$html .= "<td>Frequencia</td>";
	$html .= "<td>Frequencia Adivivo</td>";

	$html .= "<td>Tipo Coleta</td>";
	$html .= "<td>Tipo Coleta Adivivo</td>";

	$html .= "<td>Quantidade</td>";
	$html .= "<td>Quantidade Adivivo</td>";

	$html .= "<td>Vl Unitario</td>";
	$html .= "<td>Vl Unitario Adivivo</td>";

	$html .= "<td>Vl Extra</td>";
	$html .= "<td>Vl Extra Adivivo</td>";

	$html .= "<td>Vl Mensal</td>";
	$html .= "<td>Vl Mensal Adivivo</td>";

	$html .= "<td>Consultor</td>";
	$html .= "<td>Motivo</td>";
$html .= "</tr>";

foreach($leitura as $mostra):
	$html .= "<tr>";
		$html .= "<td>".$mostra['id']."</td>";

		$contratoId = $mostra['id_contrato'];
		$contrato = mostra('contrato',"WHERE id ='$contratoId '");

		$clienteId = $mostra['id_cliente'];
		$dataSuspensao = $mostra['data_suspensao'];

		$cliente = mostra('cliente',"WHERE id ='$clienteId '");
		$html .= "<td>".$cliente['nome']."</td>";
		 
		$html .= "<td>".converteData($mostra['aprovacao'])."</td>";
		$html .= "<td>".converteData($mostra['inicio'])."</td>";

		$html .= "<td>".$mostra['endereco']."</td>";
		$html .= "<td>".$mostra['endereco_aditivo']."</td>";

		$html .= "<td>".$mostra['frequencia']."</td>";
		$html .= "<td>".$mostra['frequencia_aditivo']."</td>";

		$tipoColetaId=$mostra['tipo_coleta'];		
		$tipoColeta = mostra('contrato_tipo_coleta',"WHERE id ='$tipoColetaId'");
	
		$html .= "<td>".$tipoColeta['nome']."</td>";

		$tipoColetaId=$mostra['tipo_coleta_aditivo'];		
		$tipoColeta = mostra('contrato_tipo_coleta',"WHERE id ='$tipoColetaId'");
	
		$html .= "<td>".$tipoColeta['nome']."</td>";

		$html .= "<td>".$mostra['quantidade']."</td>";
		$html .= "<td>".$mostra['quantidade_aditivo']."</td>";

		$html .= "<td>".converteValor($mostra['valor_unitario'])."</td>";
		$html .= "<td>".converteValor($mostra['valor_unitario_aditivo'])."</td>";

		$html .= "<td>".converteValor($mostra['valor_extra'])."</td>";
		$html .= "<td>".converteValor($mostra['valor_extra_aditivo'])."</td>";

		$html .= "<td>".converteValor($mostra['valor_mensal'])."</td>";
		$html .= "<td>".converteValor($mostra['valor_mensal_aditivo'])."</td>";
		
		$consultorId=$contrato['consultor'];
		$consultor = mostra('contrato_consultor',"WHERE id ='$consultorId'");

		$html .= "<td>".$consultor['nome']."</td>";

		$motivoId=$mostra['motivo'];		
		$motivo= mostra('contrato_aditivo_motivo',"WHERE id ='$motivoId'");
		$html .= "<td>".$motivo['nome']."</td>";

		 
	$html .= "</tr>";
endforeach;

echo $html;

?>