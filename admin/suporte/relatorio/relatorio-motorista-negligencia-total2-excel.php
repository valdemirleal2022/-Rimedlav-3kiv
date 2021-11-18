<?php

ob_start();
session_start();

require_once('../../../config/crud.php');
require_once('../../../config/funcoes.php');

$data1 = $_SESSION['inicio'];
$data2 = $_SESSION['fim'];
 
$nome_arquivo = "relatorio-motorista-negligencia-total";

header("Content-type: application/vnd.ms-excel");
header("Content-type: application/force-download");
header("Content-Disposition: attachment; filename=$nome_arquivo.xls");
header("Pragma: no-cache");
  
$html = '';
$html .= "<body>";
$html .= "<table>";
$html .= "<tbody>";
$html .= "<tr>";
	$html .= "<td>Nome</td>";
	$html .= "<td>Tipo</td>";
	$html .= "<td>Rota</td>";
	$html .= "<td>Pontuacao</td>";
	$html .= "<td>Status</td>";
$html .= "</tr>";

$leituraMotorista = read('veiculo_motorista',"WHERE id ORDER BY nome");

foreach($leituraMotorista as $mostraMotorista):

	$motoristaId =$mostraMotorista[ 'id' ];
	$totalSaldo = 0;

	$leitura = read('veiculo_motorista_negligencia',"WHERE id AND data>='$data1' AND data<='$data2' AND id_motorista='$motoristaId' ORDER BY data DESC");

	foreach($leitura as $mostra):
			
			$negligenciaId = $mostra['id_negligencia'];
			$negligencia = mostra('veiculo_motorista_motivo_negligencia',"WHERE id ='$negligenciaId'");
			$rotaId = $mostra['rota'];
			$rota = mostra('contrato_rota',"WHERE id ='$rotaId'");

			$totalSaldo = $totalSaldo + $negligencia['pontuacao'];

	endforeach;

	$listar='NAO';
	if($mostraMotorista['status']=='1'){
		$listar='SIM';
	}
 

	if($listar=='SIM'){
		
			$html .= "<tr>";
				$html .= "<td>".$mostraMotorista['nome']."</td>";
				if($mostraMotorista['tipo']=='1'){
					$html .= "<td>Motorista</td>";
				}elseif($mostraMotorista['tipo']=='2'){
					$html .= "<td>Ajudante</td>";
				}else{
					$html .= "<td>-</td>";
				}
				$html .= "<td>".$rota['nome']."</td>";

				$html .= "<td>". $totalSaldo ."</td>";
		
				$statusId = $mostraMotorista['status'];
				$status = mostra('funcionario_status',"WHERE id ='$statusId'");
			 
				$html .= "<td>". $status['nome'] ."</td>";
		
		
			$html .= "</tr>";
		
	}

		
	
				
 		
  endforeach;

echo $html;

?>