<?php

ob_start();
session_start();

require_once('../../../config/crud.php');
require_once('../../../config/funcoes.php');

$data1 = $_SESSION['inicio'];
$data2 = $_SESSION['fim'];

$veiculo=$_SESSION['veiculo'];
$tipo=$_SESSION['tipo'];
$rota=$_SESSION['rota'];

$total = conta('rota_ocorrencia',"WHERE id AND data>='$data1' AND data<='$data2' ORDER BY data ASC");

$leitura = read('rota_ocorrencia',"WHERE id AND data>='$data1' AND data<='$data2' ORDER BY data DESC, hora DESC");


	if(!empty($tipo)){
		$total = conta('rota_ocorrencia',"WHERE id AND data>='$data1' AND data<='$data2' AND id_ocorrencia='$tipo'");
		$leitura = read('rota_ocorrencia',"WHERE id AND data>='$data1' AND data<='$data2' AND id_ocorrencia='$tipo' ORDER BY data ASC");
	}
		

	if(!empty($veiculo)){
		$total = conta('rota_ocorrencia',"WHERE id AND data>='$data1' AND data<='$data2' AND id_veiculo='$veiculo'");
		$leitura = read('rota_ocorrencia',"WHERE id AND data>='$data1' AND data<='$data2' AND id_veiculo='$veiculo' ORDER BY data ASC");
	}

	if(!empty($rota)){
		$total = conta('rota_ocorrencia',"WHERE id AND data>='$data1' AND data<='$data2' AND id_rota='$rota'");
		$leitura = read('rota_ocorrencia',"WHERE id AND data>='$data1' AND data<='$data2' AND id_rota='$rota' ORDER BY data ASC");
	}
		
	if(!empty($tipo) && !empty($veiculo ) ){
		$total = conta('rota_ocorrencia',"WHERE id AND data>='$data1' AND data<='$data2' 
			AND id_veiculo='$veiculo' AND id_ocorrencia='$tipo'");
		$leitura = read('rota_ocorrencia',"WHERE id AND data>='$data1' AND data<='$data2'
			AND id_veiculo='$veiculo' AND id_ocorrencia='$tipo' ORDER BY data ASC");
	}

	if(!empty($rota) && !empty($veiculo ) ){
		$total = conta('rota_ocorrencia',"WHERE id AND data>='$data1' AND data<='$data2' 
			AND id_veiculo='$veiculo' AND id_rota='$rota'");
		$leitura = read('rota_ocorrencia',"WHERE id AND data>='$data1' AND data<='$data2'
			AND id_veiculo='$veiculo' AND id_rota='$rota' ORDER BY data ASC");
	}

	if(!empty($rota) && !empty($veiculo) && !empty($tipo) ){
		$total = conta('rota_ocorrencia',"WHERE id AND data>='$data1' AND data<='$data2' 
			AND id_veiculo='$veiculo' AND id_rota='$rota' AND id_ocorrencia='$tipo'");
		$leitura = read('rota_ocorrencia',"WHERE id AND data>='$data1' AND data<='$data2'
			AND id_veiculo='$veiculo' AND id_rota='$rota' AND id_ocorrencia='$tipo' ORDER BY data ASC");
	}
$nome_arquivo = "relatorio-rota-ocorrrencia";
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
	$html .= "<td>Rota</td>";
	$html .= "<td>Tipo Ocorrência</td>";
	$html .= "<td>Veículo</td>";
	$html .= "<td>Data</td>";
	$html .= "<td>Hora</td>";
	$html .= "<td>Ocorrência</td>";
$html .= "</tr>";
foreach($leitura as $mostra):
	$html .= "<tr>";

		$html .= "<td>".$mostra['id']."</td>";
		$rotaId = $mostra['id_rota'];
		$contratoRota = mostra('contrato_rota',"WHERE id ='$rotaId'");

		$html .= "<td>".$contratoRota['nome']."</td>";

		$ocorrenciaId = $mostra['id_ocorrencia'];
		$contratoOcorrencia = mostra('rota_ocorrencia_tipo',"WHERE id ='$ocorrenciaId'");

		$html .= "<td>".$contratoOcorrencia['nome']."</td>";
		
		$veiculoId = $mostra['id_veiculo'];
		$contratoVeiculo = mostra('veiculo',"WHERE id ='$veiculoId'");
		$html .= "<td>".$contratoVeiculo['modelo'].'|'.$contratoVeiculo['placa']."</td>";
	
		$html .= "<td>".converteData($mostra['data'])."</td>";

		$html .= "<td>".$mostra['hora']."</td>";
		$html .= "<td>".$mostra['ocorrencia']."</td>";
	$html .= "</tr>";
endforeach;

echo $html;

?>