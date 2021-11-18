<?php

ob_start();
session_start();

require_once('../../../config/crud.php');
require_once('../../../config/funcoes.php');

$dataInicio = $_SESSION['dataInicio'];
$dataFinal = $_SESSION['dataFinal'];

$leitura = read('veiculo',"WHERE id ORDER BY placa ASC");

$nome_arquivo = "relatorio-veiculo-retirada";
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
	$html .= "<td>Placa</td>";
	$html .= "<td>Data Inicio</td>";
	$html .= "<td>Data Fim</td>";
	$html .= "<td>Km Inicio</td>";
	$html .= "<td>Km Fim</td>";
	$html .= "<td>Total Abastecido</td>";
	$html .= "<td>Km Pecorrida</td>";
	$html .= "<td>Media</td>";
$html .= "</tr>";

foreach($leitura as $mostra):

	$veiculoId=$mostra['id'];
	$totalAbastecimento=0;
	$kminicio='';
	$datainicio='';

	$leituraCombustivel = read('veiculo_combustivel_retirada',"WHERE id AND data>='$dataInicio' AND data<='$dataFinal' AND id_veiculo='$veiculoId' ORDER BY data ASC");
	
		
	foreach($leituraCombustivel as $mostraCombustivel):

		if(empty($kminicio)){
			
			$kminicio = $mostraCombustivel['km'];
			$datainicio = $mostraCombustivel['data'];
			
		}

		$ultimoAbastecimento = $mostraCombustivel['quantidade'];
		$ultimokm = $mostraCombustivel['km'];
		$totalAbastecimento = $totalAbastecimento+$mostraCombustivel['quantidade'];

	endforeach;

		if(!empty($kminicio)){

			$kmPercorrido =$ultimokm-$kminicio;
			$abastecimento = $totalAbastecimento-$ultimoAbastecimento;

			$mediaKm = ($ultimokm-$kminicio)/$abastecimento;

			$html .= "<tr>";

				$html .= "<td>".$mostra['id']."</td>";
				$html .= "<td>".$mostra['placa']."</td>";
				$html .= "<td>".$datainicio."</td>";
				$html .= "<td>".$mostraCombustivel['data']."</td>";
				$html .= "<td>".$kminicio."</td>";
				$html .= "<td>".$ultimokm."</td>";
				$html .= "<td>".$abastecimento."</td>";
				$html .= "<td>".$kmPercorrido."</td>";
				$html .= "<td>".number_format($mediaKm, 2, '.', '')."</td>";

			$html .= "</tr>";
		 
		}

endforeach;

echo $html;

?>