<?php

ob_start();
session_start();

require_once('../../../config/crud.php');
require_once('../../../config/funcoes.php');

$data1 = $_SESSION['inicio'];
$data2 = $_SESSION['fim'];
 

$nome_arquivo = "relatorio-ordem-rentabilicade-pesagem-excel";
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
	$html .= "<td>Coletado</td>";
 
$html .= "</tr>";

$leituraRota = read('contrato_rota',"WHERE id ORDER BY nome ASC");

foreach($leituraRota as $mostraRota):

	$rotaId = $mostraRota['id'];

	
	$pesagemPrevista=0;
	$pesagemColetada=0;

	$leituraTipoColeta= read('contrato_tipo_coleta',"WHERE id ORDER BY id ASC");
	if($leituraTipoColeta){
		foreach($leituraTipoColeta as $tipoColeta):

			$tipoColetaId = $tipoColeta['id'];
			$pesoMedio = $tipoColeta['peso_medio'];

			$previsto=0;
			$coletado=0;
		
			$leitura = read('contrato_ordem',"WHERE data>='$data1' AND data<='$data2' AND rota='$rotaId' AND status='13' AND tipo_coleta1='$tipoColetaId'" );
 
			foreach($leitura as $mostra):
		
 					$contratoId = $mostra['id_contrato'];

					$contratoColeta = mostra( 'contrato_coleta', "WHERE id AND inicio<='$data1' AND vencimento>='$data2' AND id_contrato='$contratoId' AND tipo_coleta='$tipoColetaId'" );

					$previsto=$previsto+$contratoColeta['quantidade'];
		
					$coletado=$coletado+$mostra['quantidade1'];
 

			endforeach;

		 $pesagemPrevista=$pesagemPrevista+($pesoMedio*$previsto);
		$pesagemColetada=$pesagemColetada+($pesoMedio*$coletado);

		endforeach;

	}

		$leitura= read('veiculo_liberacao',"WHERE saida>='$data1' AND saida<='$data2' AND rota ='$rotaId'");
		if($leitura){
			foreach($leitura as $veiculoLiberacao):

				$pesagem = $veiculoLiberacao['pesagem'] ; 

			endforeach;
		}


		$html .= "<tr>";
			$html .= "<td>".$mostraRota['nome'] ."</td>";
			$html .= "<td>".$pesagem."</td>";
			$html .= "<td>".$pesagemPrevista."</td>";
			$html .= "<td>".$pesagemColetada."</td>";
		$html .= "</tr>"; 
 
endforeach;

echo $html;

?>