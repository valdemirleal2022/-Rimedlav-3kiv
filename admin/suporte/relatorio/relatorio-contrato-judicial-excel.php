<?php

ob_start();
session_start();

require_once('../../../config/crud.php');
require_once('../../../config/funcoes.php');


$data1 = $_SESSION['inicio'];
$data2 = $_SESSION['fim'];

$valor_total = soma('contrato',"WHERE id AND status='10' AND data_judicial>='$data1' 
											  AND data_judicial<='$data2'",'valor_mensal');
$total = conta('contrato',"WHERE id AND data_cancelamento>='$data1' 
												AND data_judicial<='$data2' AND status='10'");
$leitura = read('contrato',"WHERE id AND data_judicial>='$data1' 
											  AND data_judicial<='$data2' AND status='10' 
											  ORDER BY data_judicial ASC");

$nome_arquivo = "contratos-judicial";
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
	$html .= "<td>Endereco</td>";
	$html .= "<td>Bairro</td>";
	$html .= "<td>Consultor</td>";
	$html .= "<td>Total Debito</td>";
	$html .= "<td>A partir</td>";
	$html .= "<td>Rota</td>";
	$html .= "<td>Motivo</td>";
	$html .= "<td>Interacao</td>";
$html .= "</tr>";

foreach($leitura as $mostra):
	$html .= "<tr>";
		$html .= "<td>".$mostra['id'].'|'.substr($mostra['controle'],0,6)."</td>";

		$contratoId = $mostra['id'];
		$clienteId = $mostra['id_cliente'];

		$cliente = mostra('cliente',"WHERE id ='$clienteId '");
		$html .= "<td>".$cliente['nome']."</td>";
		$endereco=substr($cliente['endereco'],0,50).','.$cliente['numero'].' - '.$cliente['complemento'];
		$html .= "<td>".$endereco."</td>";
		$html .= "<td>".$cliente['bairro']."</td>";

		$consultorId=$mostra['consultor'];
		$consultor = mostra('contrato_consultor',"WHERE id ='$consultorId'");
		$html .= "<td>".$consultor['nome']."</td>";

		$totalDebito = soma('receber',"WHERE id_cliente ='$clienteId' AND status<>'Baixado'",'valor');
		$valor_total = $valor_total +$totalDebito;

		$html .= "<td>".converteValor($totalDebito)."</td>";

		$html .= "<td>".converteData($mostra['data_judicial'])."</td>";

		if(!empty($mostra[ 'domingo_rota1' ])){
			$rotaId = $mostra[ 'domingo_rota1' ];
			$rota = mostra( 'contrato_rota', "WHERE id AND id='$rotaId'" );
			$rota= $rota[ 'nome' ];
		}
		if(!empty($mostra[ 'segunda_rota1' ])){
			$rotaId = $mostra[ 'segunda_rota1' ];
			$rota = mostra( 'contrato_rota', "WHERE id AND id='$rotaId'" );
			$rota= $rota[ 'nome' ];
		}
		if(!empty($mostra[ 'terca_rota1' ])){
			$rotaId = $mostra[ 'terca_rota1' ];
			$rota = mostra( 'contrato_rota', "WHERE id AND id='$rotaId'" );
			$rota= $rota[ 'nome' ];
		}
		if(!empty($mostra[ 'quarta_rota1' ])){
			$rotaId = $mostra[ 'quarta_rota1' ];
			$rota = mostra( 'contrato_rota', "WHERE id AND id='$rotaId'" );
			$rota= $rota[ 'nome' ];
		}
		if(!empty($mostra[ 'quinta_rota1' ])){
			$rotaId = $mostra[ 'quinta_rota1' ];
			$rota = mostra( 'contrato_rota', "WHERE id AND id='$rotaId'" );
			$rota= $rota[ 'nome' ];
		}
		if(!empty($mostra[ 'sexta_rota1' ])){
			$rotaId = $mostra[ 'sexta_rota1' ];
			$rota = mostra( 'contrato_rota', "WHERE id AND id='$rotaId'" );
			$rota= $rota[ 'nome' ];
		}
		if(!empty($mostra[ 'sabado_rota1' ])){
			$rotaId = $mostra[ 'sabado_rota1' ];
			$rota = mostra( 'contrato_rota', "WHERE id AND id='$rotaId'" );
			$rota= $rota[ 'nome' ];
		}

		$html .= "<td>".$rota."</td>";

		$contratoBaixa = mostra('contrato_baixa',"WHERE id_contrato ='$contratoId' AND tipo='2' ORDER BY interacao ASC");
		$html .= "<td>". substr($contratoBaixa['motivo'],0,60) ."</td>";
			
		$statusId=$mostra['status'];
		$status = mostra('contrato_status',"WHERE id ='$statusId'");
		$html .= "<td>".date('d/m/Y H:i:s',strtotime($contratoBaixa['interacao']))."</td>";

		


		
	$html .= "</tr>";
endforeach;

echo $html;

?>