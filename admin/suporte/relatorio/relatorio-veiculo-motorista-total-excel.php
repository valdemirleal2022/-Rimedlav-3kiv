<?php

ob_start();
session_start();

require_once('../../../config/crud.php');
require_once('../../../config/funcoes.php');

$dataEmissao1 = $_SESSION['dataEmissao1'];
$dataEmissao2 = $_SESSION['dataEmissao2'];
$parcial = $_SESSION['parcial'];

$nome_arquivo = "relatorio-veiculo-motorista-total";
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
	$html .= "<td>Parcial</td>";
	$html .= "<td>Saldo</td>";
$html .= "</tr>";

$leituraMotorista = read('veiculo_motorista',"WHERE id ORDER BY nome");

if(!empty($parcial)){
	$total = conta('veiculo_motorista',"WHERE id AND parcial='1'");
	$leitura = read('veiculo_motorista',"WHERE id AND parcial='1' ORDER BY nome");
}

foreach($leituraMotorista as $mostraMotorista):

	$motoristaId =$mostraMotorista[ 'id' ];
	$totalSaldo = 0;

	$leitura = read('veiculo_liberacao',"WHERE id AND saida>='$dataEmissao1' AND saida<='$dataEmissao2' AND id_veiculo<>'72' ORDER BY saida ASC");

	foreach($leitura as $mostra):

		$listar='NAO';
		if($motoristaId==$mostra['motorista']){
			$listar='SIM';
			if($mostra['motorista_diarista']=='1'){
				$listar='NAO';
			}
		}
		if($motoristaId==$mostra['ajudante1']){
			$listar='SIM';
			if($mostra['ajudante1_diarista']=='1'){
				$listar='NAO';
			}
		}
		if($motoristaId==$mostra['ajudante2']){
			$listar='SIM';
			if($mostra['ajudante2_diarista']=='1'){
				$listar='NAO';
			}
		}

	 
		if($listar=='SIM'){
			//$saldo =$mostra['horas_trabalhadas']-588;
			$saldo =$mostra['horas_trabalhadas'];
			$totalSaldo = $totalSaldo + $saldo;
		}

		if($mostra['status']=='2'){
			$listar='NAO';
		}
		if($mostra['status']=='3'){
			$listar='NAO';
		}

	endforeach;

	if($totalSaldo>0){
		
			$html .= "<tr>";
				$html .= "<td>".$mostraMotorista['nome']."</td>";
				if($mostraMotorista['tipo']=='1'){
					$html .= "<td>Motorista</td>";
				}elseif($mostraMotorista['tipo']=='2'){
					$html .= "<td>Ajudante</td>";
				}else{
					$html .= "<td>-</td>";
				}

				if($mostraMotorista['parcial']=='1'){
					$html .= "<td>Parcial</td>";
				}else{
					$html .= "<td>-</td>";
				}
				 
				$html .= "<td>". $totalSaldo ."</td>";

				
			$html .= "</tr>";

	}
		
  endforeach;

echo $html;

?>