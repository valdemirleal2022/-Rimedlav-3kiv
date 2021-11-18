<?php

ob_start();
session_start();

require_once('../../../config/crud.php');
require_once('../../../config/funcoes.php');

$data1 = $_SESSION['inicio'];
$data2 = $_SESSION['fim'];

$total = conta('estoque_etiqueta_retirada',"WHERE id AND status='Baixado' AND data_solicitacao>='$data1' 
					 AND data_solicitacao<='$data2'");
$leitura = read('estoque_etiqueta_retirada',"WHERE id AND status='Baixado' AND data_solicitacao>='$data1' 
					 AND data_solicitacao<='$data2' ORDER BY data_solicitacao");

$nome_arquivo = "relatorio-etiqueta-retirada";
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
	$html .= "<td>Etiqueta</td>";
	$html .= "<td>Bairro</td>";
	$html .= "<td>Endereco</td>";
	$html .= "<td>Cliente</td>";
	$html .= "<td>Frequencia</td>";
	$html .= "<td>Dia da Semana</td>";
	$html .= "<td>Rota</td>";
	$html .= "<td>Quant</td>";
	$html .= "<td>Solicitacao</td>";
	$html .= "<td>Entrega</td>";
	$html .= "<td>Status</td>";

$html .= "</tr>";

foreach($leitura as $mostra):
	$html .= "<tr>";

		$html .= "<td>".$mostra['id']."</td>";

		$etiquetaId = $mostra['id_etiqueta'];
		$contratoId = $mostra['id_contrato'];
		$clienteId = $mostra['id_cliente'];

		$clienteId = $mostra['id_cliente'];
		$cliente = mostra('cliente',"WHERE id ='$clienteId '");

		$etiqueta = mostra('estoque_etiqueta',"WHERE id ='$etiquetaId'");

		$html .= "<td>".$etiqueta['nome']."</td>";

		$endereco=substr($cliente['endereco'],0,50).','.$cliente['numero'].' - '.$cliente['complemento'];

		$html .= "<td>".$cliente['bairro']."</td>";
		$html .= "<td>".$endereco."</td>";
		$html .= "<td>".$cliente['nome']."</td>";
	 	
		$contrato = mostra('contrato',"WHERE id ='$contratoId '");
	
		$diaSemana='';
		$rota='';
		if($contrato['domingo']==1){
			$diaSemana = ' D';
			$rota=$contrato['domingo_rota1'];
		}
		if($contrato['segunda']==1){
			$diaSemana = $diaSemana . ' S';
			$rota=$contrato[ 'segunda_rota1' ];
		}
		if($contrato['terca']==1){
			$diaSemana = $diaSemana . ' T';
			$rota=$contrato[ 'terca_rota1' ];
		}
		if($contrato['quarta']==1){
			$diaSemana = $diaSemana . ' Q';
			$rota=$contrato[ 'quarta_rota1' ];
		}
		if($contrato['quinta']==1){
			$diaSemana = $diaSemana . ' Q';
			$rota=$contrato[ 'quinta_rota1' ];
		}
		if($contrato['sexta']==1){
			$diaSemana = $diaSemana . ' S';
			$rota=$contrato[ 'sexta_rota1' ];
		}
		if($contrato['sabado']==1){
			$diaSemana = $diaSemana . ' S';
			$rota=$contrato[ 'sabado_rota1' ];
		}


		// 1 - semanal - 2 quinzenal - 3 mensal - 4 avulso
		$frequenciaId = $contrato[ 'frequencia' ];
		$frequencia = mostra( 'contrato_frequencia', "WHERE id AND id='$frequenciaId'" );
		$frequencia = $frequencia[ 'nome' ];

		$rota = mostra( 'contrato_rota', "WHERE id AND id='$rota'" );
		$rota = $rota[ 'nome' ];


		$html .= "<td>".$frequencia."</td>";
		$html .= "<td>".$diaSemana."</td>";
		$html .= "<td>".$rota."</td>";

		$html .= "<td>".$mostra['quantidade']."</td>";
		$html .= "<td>".converteData($mostra['data_solicitacao'])."</td>";
		$html .= "<td>".converteData($mostra['data_entrega'])."</td>";

		$html .= "<td>". $mostra['status'] ."</td>";
			
		 
	$html .= "</tr>";
endforeach;

echo $html;

?>