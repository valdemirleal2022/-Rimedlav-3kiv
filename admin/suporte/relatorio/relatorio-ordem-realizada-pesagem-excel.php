<?php

ob_start();
session_start();

require_once('../../../config/crud.php');
require_once('../../../config/funcoes.php');
 
$data1 = $_SESSION['inicio'];
$data2 = $_SESSION['fim'];

$nome_arquivo = "relatorio-ordem-realizada-pesagem2";
header("Content-type: application/vnd.ms-excel");
header("Content-type: application/force-download");
header("Content-Disposition: attachment; filename=$nome_arquivo.xls");
header("Pragma: no-cache");
 
$html = '';
$html .= "<body>";
$html .= "<table>";
$html .= "<tbody>";

$html .= "<tr>";

	$html .= "<td>Rota</td>";
	$html .= "<td>Pesagem</td>";
	$html .= "<td>Previsto</td>";
	$html .= "<td>Coletato</td>";

	$html .= "<td>". converteData($data1)."</td>";
	
$html .= "</tr>";


$leituraRota = read('contrato_rota',"WHERE id ORDER BY nome ASC");

foreach($leituraRota as $mostraRota):
	 
	$html .= "<tr>";

 		$html .= "<td>". ''.$mostraRota['nome']."</td>";
	 
		$rotaId = $mostraRota['id'];

		$pesagem = 0;
		$coletado = 0;
		$previsto = 0;

		$leituraVeiculo= read('veiculo_liberacao',"WHERE saida>='$data1' AND saida<='$data2' 
		AND rota ='$rotaId'");
		if($leituraVeiculo){
			foreach($leituraVeiculo as $veiculoLiberacao):
			
				$pesagem = $pesagem + $veiculoLiberacao['pesagem'] ; 

			endforeach;
		}

		$html .= "<td>".  $pesagem ."</td>";

		$leituraOrdem = read('contrato_ordem',"WHERE data>='$data1' AND data<='$data2' AND rota='$rotaId' AND status='13'");

		foreach($leituraOrdem as $ordem):
 
			$contratoId = $ordem['id_contrato'];
			$tipoColetaId = $ordem['tipo_coleta1'];

			$tipoColeta= mostra('contrato_tipo_coleta',"WHERE tipo_coleta='$tipoColetaId'"); $pesoMedio = $tipoColeta['peso_medio'];

			$coletado = $coletado + ($ordem['quantidade1'] * $pesoMedio);
		 
			$contratoColeta = mostra( 'contrato_coleta', "WHERE id AND inicio<='$data1' AND vencimento>='$data2' AND id_contrato='$contratoId'  AND tipo_coleta='$tipoColetaId'" );
			$previsto = $previsto + ($contratoColeta['quantidade'] * $pesoMedio);

		endforeach;
 
		$html .= "<td>".$previsto."</td>";
		$html .= "<td>".$coletado."</td>";

	 	$html .= "</tr>";
 
	endforeach;
 

echo $html;

?>