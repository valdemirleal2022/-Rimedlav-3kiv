<?php

ob_start();
session_start();

require_once('../../../config/crud.php');
require_once('../../../config/funcoes.php');

$data1 = $_SESSION['inicio'];
$data2 = $_SESSION['fim'];

$total = conta('contrato',"WHERE id AND tipo='2' AND status='5' 
			AND saldo_etiqueta_minimo>'0'");
$leitura = read('contrato',"WHERE id AND tipo='2' AND status='5' 
			AND saldo_etiqueta_minimo>'0' ORDER BY saldo_etiqueta ASC");

$nome_arquivo = "relatorio-etiqueta-negativa";
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
	$html .= "<td>Nome</td>";
	$html .= "<td>Bairro</td>";
	$html .= "<td>Frequencia</td>";
	$html .= "<td>Dia da Semana</td>";
	$html .= "<td>Rota</td>";
	$html .= "<td>Saldo Atual</td>";
	$html .= "<td>Saldo Minimo</td>";

$html .= "</tr>";

foreach($leitura as $mostra):
	$html .= "<tr>";
		$html .= "<td>".$mostra['id']."</td>";

		$clienteId = $mostra['id_cliente'];
		$clienteId = $mostra['id_cliente'];
		$cliente = mostra('cliente',"WHERE id ='$clienteId '");

		$html .= "<td>".$cliente['nome']."</td>";
		$html .= "<td>".$cliente['bairro']."</td>";
	
		$diaSemana='';
	$rota='';
	if($mostra['domingo']==1){
		$diaSemana = ' Dom';
		$rota=$mostra['domingo_rota1'];
	}
	if($mostra['segunda']==1){
		$diaSemana = $diaSemana . ' Seg';
		$rota=$mostra[ 'segunda_rota1' ];
	}
	if($mostra['terca']==1){
		$diaSemana = $diaSemana . ' Ter';
		$rota=$mostra[ 'terca_rota1' ];
	}
	if($mostra['quarta']==1){
		$diaSemana = $diaSemana . ' Qua';
		$rota=$mostra[ 'quarta_rota1' ];
	}
	if($mostra['quinta']==1){
		$diaSemana = $diaSemana . ' Qui';
		$rota=$mostra[ 'quinta_rota1' ];
	}
	if($mostra['sexta']==1){
		$diaSemana = $diaSemana . ' Sex';
		$rota=$mostra[ 'sexta_rota1' ];
	}
	if($mostra['sabado']==1){
		$diaSemana = $diaSemana . ' Sab';
		$rota=$mostra[ 'sabado_rota1' ];
	}

	// 1 - semanal - 2 quinzenal - 3 mensal - 4 avulso
	$frequenciaId = $mostra[ 'frequencia' ];
	$frequencia = mostra( 'contrato_frequencia', "WHERE id AND id='$frequenciaId'" );
	$frequencia = $frequencia[ 'nome' ];

	$rota = mostra( 'contrato_rota', "WHERE id AND id='$rota'" );
	$rota = $rota[ 'nome' ];


		$html .= "<td>".$frequencia."</td>";
		$html .= "<td>".$diaSemana."</td>";
		$html .= "<td>".$rota."</td>";

		$html .= "<td>".$mostra['saldo_etiqueta']."</td>";
		$html .= "<td>".$mostra['saldo_etiqueta_minimo'] ."</td>";
			
		 
	$html .= "</tr>";
endforeach;

echo $html;

?>