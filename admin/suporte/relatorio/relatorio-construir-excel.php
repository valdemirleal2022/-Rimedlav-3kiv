<?php

ob_start();
session_start();

require_once('../../../config/crud.php');
require_once('../../../config/funcoes.php');

$dia = $_SESSION[ 'dia' ];
$banco = $_SESSION[ 'banco' ];
$dataExtrato = $_SESSION[ 'dataExtrato' ];
$contratoTipo=$_SESSION['contratoTipo'];

$valor_total = soma('contrato',"WHERE id AND dia_fechamento='$dia' AND inicio<'$dataExtrato'",'valor_mensal');

$total = conta('contrato',"WHERE id AND dia_fechamento='$dia' AND inicio<'$dataExtrato'");

$leitura = read('contrato',"WHERE id AND dia_fechamento='$dia' AND inicio<'$dataExtrato'ORDER BY controle ASC");

if(!empty($contratoTipo)){
	$total = conta('contrato',"WHERE id AND dia_fechamento='$dia' AND contrato_tipo='$contratoTipo' AND inicio<'$dataExtrato' AND status<>'9'");
	
	$leitura = read('contrato',"WHERE id AND dia_fechamento='$dia' AND status<>'9' 
					AND inicio<'$dataExtrato' AND contrato_tipo='$contratoTipo'
					ORDER BY controle ASC");
}

$dataAnterior1=diminuirMes($dataExtrato,1);
$dataAnterior2=diminuirMes($dataAnterior1,1);

$nome_arquivo = "relatorio-faturamento";
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
	$html .= "<td>Controle</td>";
	$html .= "<td>Nome</td>";
	$html .= "<td>Tipo de Contrato</td>";
	$html .= "<td>Valor Mensal</td>";
	$html .= "<td>Inicio</td>";
	$html .= "<td>Fech</td>";
	$html .= "<td>Venc</td>";
	$html .= "<td>Emissao</td>";
	$html .= "<td>Vencimento</td>";
	$html .= "<td>Faturado</td>";
	$html .= "<td>".converteData($dataAnterior2)."</td>";
	$html .= "<td>".converteData($dataAnterior1)."</td>";
	$html .= "<td>Diferenca</td>";
	$html .= "<td>Status</td>";
	$html .= "<td>Data da Acao</td>";


	$html .= "<td>Qt Dias Faturamento</td>";
	$html .= "<td>Qt Coletado Faturamento</td>";
	$html .= "<td>Qt Dias".converteData($dataAnterior2)."</td>";
	$html .= "<td>Qt Coletado".converteData($dataAnterior2)."</td>";
	$html .= "<td>Ultimo Faturamento</td>";
	

$html .= "</tr>";

$totalAtivos = 0;
$valorAtivosAnterior = 0;
$valorAtivos = 0;

$totalSuspensos = 0;
$valorSuspensosAnterior = 0;

$valorSuspensos = 0;
$totalCancelados = 0;
$valorCanceladosAnterior = 0;
$valorCancelados = 0;

$totalJuridos = 0;
$valorJuridosAnterior = 0;
$valorJuridos = 0;

$totalNovos= 0;
$valorNovos = 0;
	
foreach($leitura as $contrato):

	$listar='SIM';

	if($contrato['status']==9){
		$dataCancelamento = date("Y-m-d", strtotime("$dataExtrato - 3 MONTH"));
		if($contrato['data_cancelamento']<$dataExtrato){
			$listar='NAO';
		}
	}

	if($listar=='SIM'){
	
	  $html .= "<tr>";

		$html .= "<td>".$contrato['id']."</td>";
		$html .= "<td>".substr($contrato['controle'],0,6)."</td>";

		$clienteId = $contrato['id_cliente'];
		$cliente = mostra('cliente',"WHERE id ='$clienteId '");
		$html .= "<td>".$cliente['nome']."</td>";

		$tipoId = $contrato['contrato_tipo'];
		$tipo = mostra('contrato_tipo',"WHERE id ='$tipoId'");
		$html .= "<td>".$tipo['nome']."</td>";

		$fechamento = $contrato['dia_fechamento'];
		$vencimento = $contrato['dia_vencimento'];

		$mes = date( 'm', strtotime( $dataExtrato ) );
		$ano = date( 'Y', strtotime( $dataExtrato ) );

		$faturamento =  date( "Y-m-d",mktime(0,0,0,$mes,$fechamento,$ano));
		$vencimento = date( "Y-m-d",mktime(0,0,0,$mes,$vencimento,$ano));

		$data1 = date("Y-m-d", strtotime("$faturamento -1 month"));
		$data2 = date("Y-m-d", strtotime("$faturamento -1 days"));

		$contratoId = $contrato['id'];
		$tipoColeta = mostra( 'contrato_coleta', "WHERE id AND inicio<='$data1' 
					AND vencimento>='$data2' AND id_contrato='$contratoId'" );

		$html .= "<td>".converteValor($tipoColeta['valor_mensal'])."</td>";
		$html .= "<td>".converteData($contrato['inicio'])."</td>";
		$html .= "<td>".$contrato['dia_fechamento']."</td>";
		$html .= "<td>".$contrato['dia_vencimento']."</td>";

		$receber = mostra('receber',"WHERE id_cliente='$clienteId' AND emissao='$dataExtrato'");
		$valorFaturado=$receber['valor']+$receber['juros']-$receber['desconto'];

		$html .= "<td>".converteData($receber['emissao'])."</td>";
		$html .= "<td>".converteData($receber['vencimento'])."</td>";
		$html .= "<td>".converteValor($valorFaturado)."</td>";
		
		$valorAnteiro2=0;
		$receber = mostra('receber',"WHERE id_cliente='$clienteId' AND emissao='$dataAnterior2'");
		if($receber){
			$valorAnteiro2=$receber['valor']+$receber['juros']-$receber['desconto'];
		}

		$valorAnteiro1=0;
		$receber = mostra('receber',"WHERE id_cliente='$clienteId' AND emissao='$dataAnterior1'");
		if($receber){
			$valorAnteiro1=$receber['valor']+$receber['juros']-$receber['desconto'];
		}

		$html .= "<td>".converteValor($valorAnteiro2)."</td>";
		$html .= "<td>".converteValor($valorAnteiro1)."</td>";

		$ValorDiferenca=$valorFaturado-$valorAnteiro1;

		$html .= "<td>".converteValor($ValorDiferenca)."</td>";
		
		
		$dataAcao='';
		
		if($contrato['status']==5){
			
			$html .= "<td>Ativo</td>";
			
			$totalAtivos = $totalAtivos+1;
			$valorAtivos = $valorAtivos+$valorFaturado;
			$valorAtivosAnterior = $valorAtivosAnterior+$valorAnteiro1;
					 
		}elseif($contrato['status']==6){
			
			$html .= "<td>Suspenso</td>";
			
			$totalSuspensos = $totalSuspensos+1;
			$valorSuspensos = $valorSuspensos+$valorFaturado;
			$valorSuspensosAnterior = $valorSuspensosAnterior+$valorAnteiro1;
			
			$dataAcao=converteData($contrato['data_suspensao']);
		 		 
		}elseif($contrato['status']==9){
			
			$html .= "<td>Cancelado</td>";
			 
			$totalCancelados = $totalCancelados+1;
			$valorCancelados = $valorCancelados+$valorFaturado;
			$valorCanceladosAnterior = $valorCanceladosAnterior+$valorAnteiro1;
			$dataAcao=converteData($contrato['data_cancelamento']);
		 				
		}elseif($contrato['status']==10){

			$totalJuridicos = $totalJuridicos+1;
			$valorJuridicos = $valorJuridicos+$valorFaturado;
			$valorJuridicosAnterior = $valorJuridicosAnterior+$valorAnteiro1;

			$html .= "<td>Juridico</td>";
			$dataAcao=converteData($contrato['data_judicial']);
		
		}elseif($contrato['status']==18){

			$totalSuspensos = $totalSuspensos+1;
			$valorSuspensos = $valorSuspensos+$valorFaturado;
			$valorSuspensosAnterior = $valorSuspensosAnterior+$valorAnteiro1;
			$html .= "<td>Suspenso Temporario</td>";
			$dataAcao=converteData($contrato['data_suspensao']);
			
		}else{
			
			$html .= "<td>!</td>";
		}
		
		
		$html .= "<td>".$dataAcao."</td>";
	
		$dataNovos = date("Y-m-d", strtotime("$dataExtrato - 1 MONTH"));
//
//		if($contrato['inicio']>=$dataNovos){
//			$html .= "<td>* Novo</td>";
//			$totalNovos = $totalNovos+1;
//			$valorNovos = $valorNovos+$valorFaturado;
//		}else{
//			$html .= "<td>-</td>";
//		}
			
 	
		// GERANDO DATAS DO EXTRATO
		$fechamento = $contrato['dia_fechamento'];
		$vencimento = $contrato['dia_vencimento'];

		// FEVEREIRO AJUSTA DIA 29 E 30 PARA DIA 28
		$mesFaturamento = numeroMes($dataExtrato);
		$dia=$fechamento;

		if($mesFaturamento=='02' AND $dia>'28'){
			$fechamento='28';
		}

		// AJUSTADA DATA DE FATURAMENTO E VENCIMENTO
		$mes = date( 'm', strtotime( $dataExtrato ) );
		$ano = date( 'Y', strtotime( $dataExtrato ) );
		$faturamento =  date( "Y-m-d",mktime(0,0,0,$mes,$fechamento,$ano));
		$vencimento = date( "Y-m-d",mktime(0,0,0,$mes,$vencimento,$ano));

		// VENCIMENTO PROXIMO MES
		if($vencimento<$faturamento){
			$vencimento = date("Y-m-d", strtotime("$vencimento +1 month"));
		}

		$data1 = date( "Y-m-d", strtotime( "$faturamento -1 month" ) );
		$data2 = date( "Y-m-d", strtotime( "$faturamento -1 days" ) );

		// FEVEREIRO AJUSTA DIA 28
		if($mesFaturamento=='02' AND $dia=='28'){
			$data1 = date( "Y-m-d", strtotime( "$faturamento -1 month" ) );
		}

		// FEVEREIRO AJUSTA DIA 29
		if($mesFaturamento=='02' AND $dia=='29'){
			$data1 = date( "Y-m-d", strtotime( "$faturamento -1 month" ) );
			$data1 = date( "Y-m-d", strtotime( "$data1 +1 days" ) );
			$data2 = date( "Y-m-d", strtotime( "$faturamento" ) );
		}

		// FEVEREIRO AJUSTA DIA 30
		if($mesFaturamento=='02' AND $dia=='30'){
			$data1 = date( "Y-m-d", strtotime( "$faturamento -1 month" ) );
			$data1 = date( "Y-m-d", strtotime( "$data1 +2 days" ) );

			// ANO BISEXTO
			$data2 = date( "Y-m-d", strtotime( "$faturamento +1 days" ) );
		}


		// MARÇO AJUSTA DIA 30
		if($mesFaturamento=='03' AND $dia=='30'){
			$data1 = date( "Y-m-d", strtotime( "$faturamento -1 month" ) );
			$data1 = date( "Y-m-d", strtotime( "$data1 -1 days" ) );
			$data2 = date( "Y-m-d", strtotime( "$faturamento" ) );
		}

		$dias = conta('contrato_ordem',"WHERE id AND id_contrato='$contratoId'  
					AND data>='$data1' AND data<='$data2'");
		$qtd = soma('contrato_ordem',"WHERE id AND id_contrato='$contratoId'  
					AND data>='$data1' AND data<='$data2'",'quantidade1');
		
		$html .= "<td>".$dias."</td>";
		$html .= "<td>".$qtd."</td>";

		// GERANDO DATAS DO EXTRATO
		$fechamento = $contrato['dia_fechamento'];
		$vencimento = $contrato['dia_vencimento'];

		// FEVEREIRO AJUSTA DIA 29 E 30 PARA DIA 28
		$mesFaturamento = numeroMes($dataNovos);
		$dia=$fechamento;

		if($mesFaturamento=='02' AND $dia>'28'){
			$fechamento='28';
		}

		// AJUSTADA DATA DE FATURAMENTO E VENCIMENTO
		$mes = date( 'm', strtotime( $dataNovos ) );
		$ano = date( 'Y', strtotime( $dataNovos ) );
		$faturamento =  date( "Y-m-d",mktime(0,0,0,$mes,$fechamento,$ano));
		$vencimento = date( "Y-m-d",mktime(0,0,0,$mes,$vencimento,$ano));

		// VENCIMENTO PROXIMO MES
		if($vencimento<$faturamento){
			$vencimento = date("Y-m-d", strtotime("$vencimento +1 month"));
		}

		$data1 = date( "Y-m-d", strtotime( "$faturamento -1 month" ) );
		$data2 = date( "Y-m-d", strtotime( "$faturamento -1 days" ) );

		// FEVEREIRO AJUSTA DIA 28
		if($mesFaturamento=='02' AND $dia=='28'){
			$data1 = date( "Y-m-d", strtotime( "$faturamento -1 month" ) );
		}

		// FEVEREIRO AJUSTA DIA 29
		if($mesFaturamento=='02' AND $dia=='29'){
			$data1 = date( "Y-m-d", strtotime( "$faturamento -1 month" ) );
			$data1 = date( "Y-m-d", strtotime( "$data1 +1 days" ) );
			$data2 = date( "Y-m-d", strtotime( "$faturamento" ) );
		}

		// FEVEREIRO AJUSTA DIA 30
		if($mesFaturamento=='02' AND $dia=='30'){
			$data1 = date( "Y-m-d", strtotime( "$faturamento -1 month" ) );
			$data1 = date( "Y-m-d", strtotime( "$data1 +2 days" ) );

			// ANO BISEXTO
			$data2 = date( "Y-m-d", strtotime( "$faturamento +1 days" ) );
		}

		// MARÇO AJUSTA DIA 30
		if($mesFaturamento=='03' AND $dia=='30'){
			$data1 = date( "Y-m-d", strtotime( "$faturamento -1 month" ) );
			$data1 = date( "Y-m-d", strtotime( "$data1 -1 days" ) );
			$data2 = date( "Y-m-d", strtotime( "$faturamento" ) );
		}

		// ORDEM DE SERVIÇO GERADAS
		$dias = conta('contrato_ordem',"WHERE id AND id_contrato='$contratoId'  
					AND data>='$data1' AND data<='$data2'");
		$qtd = soma('contrato_ordem',"WHERE id AND id_contrato='$contratoId'  
					AND data>='$data1' AND data<='$data2'",'quantidade1');
		
		$html .= "<td>".$dias."</td>";
		$html .= "<td>".$qtd."</td>";
		
		$dataAnterior=diminuirMes($dataExtrato,1);
		$receber = mostra('receber',"WHERE id_cliente='$clienteId' AND emissao>'$dataAnterior' AND emissao<'$dataExtrato'");
	 	
		$html .= "<td>".converteData($receber['emissao'])."</td>";
		
	  $html .= "</tr>";
 
	}

endforeach;

$html .= "<tr>";
	$html .= "<td></td>";
	$html .= "<td></td>";
	$html .= "<td>Status</td>";
	$html .= "<td>Total</td>";
	$html .= "<td>Faturado</td>";
	$html .= "<td>".converteData($dataAnterior1)."</td>";
$html .= "</tr>";

$html .= "<tr>";
	$html .= "<td></td>";
	$html .= "<td></td>";
	$html .= "<td>Contrato Ativos</td>";
	$html .= "<td>".$totalAtivos."</td>";
	$html .= "<td>".converteValor($valorAtivos)."</td>";
	$html .= "<td>".converteValor($valorAtivosAnterior)."</td>";
$html .= "</tr>";

$html .= "<tr>";
	$html .= "<td></td>";
	$html .= "<td></td>";
	$html .= "<td>Contrato Suspensos</td>";
	$html .= "<td>".$totalSuspensos."</td>";
	$html .= "<td>".converteValor($valorSuspensos)."</td>";
	$html .= "<td>".converteValor($valorSuspensosAnterior)."</td>";
$html .= "</tr>";

$html .= "<tr>";
	$html .= "<td>Contrato no Juridico</td>";
	$html .= "<td>".$totalJuridicos."</td>";
	$html .= "<td>".converteValor($valorJuridicos)."</td>";
	$html .= "<td>".converteValor($valorJuridicosAnterior)."</td>";
$html .= "</tr>";


$html .= "<tr>";
	$html .= "<td></td>";
	$html .= "<td></td>";
	$html .= "<td>Contrato Cancelados</td>";
	$html .= "<td>".$totalCancelados."</td>";
	$html .= "<td>".converteValor($valorCancelados)."</td>";
	$html .= "<td>".converteValor($valorCanceladosAnterior)."</td>";
$html .= "</tr>";

$html .= "<tr>";
	$html .= "<td></td>";
	$html .= "<td></td>";
	$html .= "<td>Contrato Novos</td>";
	$html .= "<td>".$totalNovos."</td>";
	$html .= "<td>".converteValor($valorNovos)."</td>";
	$html .= "<td>".converteValor($valorNovosAnterior)."</td>";
$html .= "</tr>";

echo $html;

?>