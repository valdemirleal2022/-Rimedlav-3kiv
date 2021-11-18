<?php

ob_start();
session_start();

require_once('../../../config/crud.php');
require_once('../../../config/funcoes.php');

$data1 = $_SESSION['data1'];
$data2 = $_SESSION['data2'];

if(isset($_SESSION[ 'veiculo' ])){
	$veiculoId =$_SESSION[ 'veiculo' ];
	$veiculo = mostra('veiculo',"WHERE id ='$veiculoId'");
	$veiculoNome = $veiculo['modelo'].' | '.$veiculo['placa'];
}


$leitura = read('veiculo_abastecimento',"WHERE id AND data>='$data1' AND data<='$data2' ORDER BY data DESC");

if(!empty($veiculoId)){
	$leitura = read('veiculo_abastecimento',"WHERE id AND data>='$data1' AND data<='$data2' AND id_veiculo='$veiculoId' ORDER BY data ASC");
}

$nome_arquivo = "relatorio-veiculo-abastecimento";
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
	$html .= "<td>Data</td>";
	$html .= "<td>Motorista</td>";
	$html .= "<td>Combustivel</td>";
	$html .= "<td>Quantidade</td>";
	$html .= "<td>Valor</td>";
	$html .= "<td>Km</td>";
$html .= "</tr>";
foreach($leitura as $mostra):
	$html .= "<tr>";

		$html .= "<td>".$mostra['id']."</td>";
		$veiculoId = $mostra['id_veiculo'];
		$veiculo = mostra('veiculo',"WHERE id ='$veiculoId'");
 
		$html .= "<td>".$veiculo['placa']."</td>";
	
		$html .= "<td>".converteData($mostra['data'])."</td>";

		$motoristaId = $mostra['id_motorista'];
		$motorista = mostra('veiculo_motorista',"WHERE id ='$motoristaId'");
		$html .= "<td>".$motorista['nome']."</td>";

		$tipoId = $mostra['tipo_combustivel'];
		$tipo = mostra('veiculo_motorista',"WHERE id ='$motoristaId'");
		$html .= "<td>".$tipo['nome']."</td>";
	
		$html .= "<td>".$mostra['quantidade']."</td>";
		$html .= "<td>".converteValor($mostra['valor'])."</td>";
		$html .= "<td>".$mostra['km']."</td>";
	$html .= "</tr>";
endforeach;

echo $html;

?>