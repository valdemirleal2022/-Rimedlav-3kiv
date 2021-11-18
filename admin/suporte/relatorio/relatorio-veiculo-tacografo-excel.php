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


$leitura = read('veiculo_tacografo',"WHERE id AND data_troca>='$data1' AND data_troca<='$data2' ORDER BY data_troca ASC");

if(!empty($veiculoId)){
	$leitura = read('veiculo_tacografo',"WHERE id AND data_troca>='$data1' AND data_troca<='$data2' AND id_veiculo='$veiculoId' ORDER BY data ASC");
}

$nome_arquivo = "relatorio-veiculo-tarcografo";
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
	$html .= "<td>Data Troca</td>";
	$html .= "<td>Data Prevista</td>";
	$html .= "<td>Observacao</td>";
	$html .= "<td>Status</td>";
 
$html .= "</tr>";
foreach($leitura as $mostra):
	$html .= "<tr>";

		$html .= "<td>".$mostra['id']."</td>";
		$veiculoId = $mostra['id_veiculo'];
		$veiculo = mostra('veiculo',"WHERE id ='$veiculoId'");
 
		$html .= "<td>".$veiculo['placa']."</td>";
	
		$html .= "<td>".converteData($mostra['data_troca'])."</td>";
 
		$html .= "<td>".$mostra['observacao']."</td>";

		if($mostra['status']==1){
     
			$html .= "<td>Realizada</td>";
			
           }else{
			
            $html .= "<td>-</td>";
			
        }
		
		
	$html .= "</tr>";
endforeach;

echo $html;

?>