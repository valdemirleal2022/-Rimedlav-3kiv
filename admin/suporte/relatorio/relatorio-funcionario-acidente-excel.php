<?php

ob_start();
session_start();

require_once('../../../config/crud.php');
require_once('../../../config/funcoes.php');
 
$data1 = $_SESSION['inicio'];
$data2 = $_SESSION['fim'];
 
$total = conta('funcionario_acidente',"WHERE id AND data>='$data1' AND data<='$data2' 
ORDER BY data ASC ");
$leitura = read('funcionario_acidente',"WHERE id AND data>='$data1' AND data<='$data2' 
ORDER BY data DESC");

$nome_arquivo = "relatorio-funcionario-acidente";
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
	$html .= "<td>Funcionário</td>";
	$html .= "<td>Veículo</td>";
	$html .= "<td>Tipo</td>";
	$html .= "<td>Data</td>";
	$html .= "<td>Local</td>";
	$html .= "<td>Agente</td>";
	$html .= "<td>Status</td>";
	$html .= "<td>Seguradora</td>";
	$html .= "<td>Status do Seguro</td>";
	
$html .= "</tr>";
foreach($leitura as $mostra):
	$html .= "<tr>";

		$html .= "<td>".$mostra['id']."</td>";
		$funcionarioId = $mostra['id_funcionario'];
			
		$funcionario = mostra('funcionario',"WHERE id ='$funcionarioId '");
		$html .= "<td>".$funcionario['nome']."</td>";

		$veiculoId = $mostra['id_veiculo'];
		$veiculo = mostra('veiculo',"WHERE id ='$veiculoId '");
		$html .= "<td>".$veiculo['placa']."</td>";

		$tipoId = $mostra['tipo_acidente'];
		$tipo = mostra('funcionario_acidente_tipo',"WHERE id ='$tipoId'");
		$html .= "<td>".$tipo['nome']."</td>";

		$html .= "<td>".converteData($mostra['data'])."</td>";
		$html .= "<td>".$mostra['local'] ."</td>";

		$html .= "<td>".$mostra['agente_causador']."</td>";
		if($mostra['status']=='1'){
			$html .= "<td>Advertência</td>";
 
		}else if($mostra['status']=='2'){
			$html .= "<td>NA</td>";
		 
		}else if($mostra['status']=='2'){
			$html .= "<td>-</td>";
		
		}

		if($mostra['acionou_seguradora']=='1'){
			$html .= "<td>Sim</td>";
		}else if($mostra['acionou_seguradora']=='2'){
			$html .= "<td>Não</td>";
		}else if($mostra['acionou_seguradora']=='2'){
			$html .= "<td>-</td>";
		}

		if($mostra['status_seguradora']=='1'){
			$html .= "<td>Em Andamento</td>";
		}else if($mostra['status_seguradora']=='2'){
			$html .= "<td>Concluído</td>";
		}else{
			$html .= "<td>Não</td>";
		}
   	 	
		
	$html .= "</tr>";
endforeach;

echo $html;

?>