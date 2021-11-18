<?php

ob_start();
session_start();

require_once('../../../config/crud.php');
require_once('../../../config/funcoes.php');

$dataEmissao1 = $_SESSION['dataEmissao1'];
$dataEmissao2 = $_SESSION['dataEmissao2'];

$motoristaId =$_SESSION[ 'motorista' ];
$motorista = mostra('veiculo_motorista',"WHERE id ='$motoristaId'");

$leitura = read('veiculo_liberacao',"WHERE id AND saida>='$dataEmissao1' AND saida<='$dataEmissao2' AND id_veiculo<>'72' ORDER BY saida ASC");

$totalMinutos=0;
$totalSaldo=0;

$nome_arquivo = "relatorio-veiculo-motorista";
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
	$html .= "<td>Veículo</td>";
	$html .= "<td>Placa</td>";
	$html .= "<td>Saida</td>";
	$html .= "<td>Hora</td>";
	$html .= "<td>Chegada</td>";
	$html .= "<td>Hora</td>";
	$html .= "<td>Rota</td>";
	$html .= "<td>Tot Min</td>";
	$html .= "<td>H Trab</td>";
	$html .= "<td>Saldo</td>";
	$html .= "<td>Status</td>";
	$html .= "<td>Status</td>";
$html .= "</tr>";

foreach($leitura as $mostra):

		$listar='NAO';
		if($motoristaId==$mostra['motorista']){
			$listar='SIM';
		}
		if($motoristaId==$mostra['ajudante1']){
			$listar='SIM';
		}
		if($motoristaId==$mostra['ajudante2']){
			$listar='SIM';
		}

		if($mostra['status']=='2'){
			$listar='NAO';
		}
		if($mostra['status']=='3'){
			$listar='NAO';
		}


		if($listar=='SIM'){

		$html .= "<tr>";
			$html .= "<td>".$mostra['id']."</td>";
			$veiculoId = $mostra['id_veiculo'];
			$veiculo = mostra('veiculo',"WHERE id ='$veiculoId'");

			$html .= "<td>".$veiculo['modelo']."</td>";
			$html .= "<td>".$veiculo['placa']."</td>";

			$html .= "<td>".converteData($mostra['saida'])."</td>";
			$html .= "<td>".$mostra['saida_hora']."</td>";

			$html .= "<td>".converteData($mostra['chegada'])."</td>";
			$html .= "<td>".$mostra['chegada_hora']."</td>";

			$rotaId = $mostra['rota'];
			$rota = mostra('contrato_rota',"WHERE id ='$rotaId'");

			$html .= "<td>". $rota['nome']."</td>";

			$html .= "<td>". $mostra['horas_trabalhadas']."</td>";

			$minutos = $mostra['horas_trabalhadas'] - ($horas*60) ;
			$totalHoras = $horas.'h ' . $minutos.'min';

			$html .= "<td>". $totalHoras ."</td>";

			$saldo =$mostra['horas_trabalhadas']-588;

			$html .= "<td>". $saldo ."</td>";

			$totalMinutos = $totalMinutos + $mostra['horas_trabalhadas'];
			$totalSaldo = $totalSaldo + $saldo;

			$html .= "<td>". $totalSaldo ."</td>";

			$html .= "<td>". $mostra['status'] ."</td>";
			
			
			$veiculoId = $mostra['id_veiculo'];
			$veiculo = mostra('veiculo',"WHERE id ='$veiculoId'");

			
			if($motorista['parcial']=='1'){
				$html .= "<td>Parcial</td>";
			}else{
				echo '<td>-</td>';
			}

		$html .= "</tr>";
			
		}

endforeach;

echo $html;

?>