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


$leitura = read('veiculo_lavagem',"WHERE id AND data>='$data1' AND data<='$data2'  
						ORDER BY data DESC"); 

$total = conta('veiculo_lavagem',"WHERE id AND data>='$data1' AND data<='$data2'  
						ORDER BY data DESC"); 
		
	
if(!empty($veiculoId)){
		$leitura = read('veiculo_lavagem',"WHERE id AND data>='$data1' AND data<='$data2' AND id_veiculo='$veiculoId' ORDER BY data DESC");
		
		$total = conta('veiculo_lavagem',"WHERE id AND data>='$data1' AND data<='$data2' AND id_veiculo='$veiculoId' ORDER BY data DESC");
}

$nome_arquivo = "relatorio-veiculo-lavagem";
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
	$html .= "<td>Tipo</td>";
	$html .= "<td>Executor</td>";
	$html .= "<td>Observação</td>";
 
$html .= "</tr>";

foreach($leitura as $mostra):

	$html .= "<tr>";

		$html .= "<td>".$mostra['id']."</td>";

		$veiculoId = $mostra['id_veiculo'];
		$tipoId = $mostra['tipo'];
		$executorId = $mostra['executor'];
		$veiculo = mostra('veiculo',"WHERE id ='$veiculoId'");
 
		$html .= "<td>".$veiculo['placa']."</td>";
	
		$html .= "<td>".converteData($mostra['data'])."</td>";
		$lavagem = mostra('veiculo_lavagem_tipo',"WHERE id ='$tipoId'");
		$html .= "<td>".$lavagem['nome']."</td>";
		$executor= mostra('veiculo_lavagem_executor',"WHERE id ='$executorId'");
 		$html .= "<td>".$executor['nome']."</td>";
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