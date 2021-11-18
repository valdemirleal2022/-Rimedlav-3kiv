<?php

ob_start();
session_start();

require_once('../../../config/crud.php');
require_once('../../../config/funcoes.php');

$dataInicio = $_SESSION['dataInicio'];
$dataFinal = $_SESSION['dataFinal'];

if(isset($_SESSION[ 'veiculo' ])){
	$veiculoId =$_SESSION[ 'veiculo' ];
	$veiculo = mostra('veiculo',"WHERE id ='$veiculoId'");
	$veiculoNome = $veiculo['modelo'].' | '.$veiculo['placa'];
}


$leitura = read('veiculo_combustivel_retirada',"WHERE id AND data>='$dataInicio' AND data<='$dataFinal' ORDER BY data ASC"); 
	$total = conta('veiculo_combustivel_retirada',"WHERE id AND data>='$dataInicio' AND data<='$dataFinal' 
						ORDER BY data ASC"); 

	if(!empty($veiculo)){
		
		$leitura = read('veiculo_combustivel_retirada',"WHERE id AND data>='$dataInicio' AND data<='dataFinal' AND id_veiculo='$veiculo' ORDER BY data ASC"); 
		$total = conta('veiculo_combustivel_retirada',"WHERE id AND data>='$dataInicio' AND data<='dataFinal' AND id_veiculo='$veiculo' ORDER BY data ASC"); 
		
	}

$nome_arquivo = "relatorio-veiculo-combustivel-retirada";
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
	$html .= "<td> Placa </td>";
	$html .= "<td> Motorista </td>";
	$html .= "<td> Combustivel 1 </td>";
	$html .= "<td> Quantidade 1 </td>";
	$html .= "<td> Combustivel 2 </td>";
	$html .= "<td> Quantidade 2 </td>";
	$html .= "<td> Data </td>";
	$html .= "<td> Km Percorrido </td>";
	$html .= "<td> Media </td>";

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
				$html .= "<td>". $motorista['nome']."</td>";
			
				$combustivelId = $mostra['id_combustivel'];
				$veiculoCombustivel = mostra('veiculo_combustivel',"WHERE id ='$combustivelId'");
				$html .= "<td>". $veiculoCombustivel['nome'] ."</td>";
				$html .= "<td>". $mostra['quantidade2']. "</td>";
				$combustivelId = $mostra['id_combustivel2'];
			 
				$veiculoCombustivel = mostra('veiculo_combustivel',"WHERE id ='$combustivelId'");
				$html .= "<td>". $veiculoCombustivel['nome'] ."</td>";
				$html .= "<td>". $mostra['quantidade2']. "</td>";

				$html .= "<td>". $mostra['km'] ."</td>";
				$html .= "<td>". converteData($mostra['data']) ." </td>";

				$html .= "<td>". $mostra['km_percorrido'] ." </td>";
				$html .= "<td>". $mostra['media'] ." </td>";
		 
			$html .= "</tr>";
	 

endforeach;

echo $html;

?>