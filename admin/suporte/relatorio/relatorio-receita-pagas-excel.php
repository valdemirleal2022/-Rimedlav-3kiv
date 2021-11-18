<?php

ob_start();
session_start();

require_once('../../../config/crud.php');
require_once('../../../config/funcoes.php');

$data1 = $_SESSION['inicio'];
$data2 = $_SESSION['fim'];
$banco = $_SESSION['banco'];
$formpag = $_SESSION['formpag'];

$leitura = read('receber',"WHERE pagamento>='$data1' AND pagamento<='$data2' AND status='Baixado' ORDER BY pagamento ASC");

$valor_total = soma('receber',"WHERE pagamento>='$data1' AND pagamento<='$data2' 
													AND status='Baixado'",'valor');
$total = conta('receber',"WHERE pagamento>='$data1' AND pagamento<='$data2' 
													AND status='Baixado'");

	if(!empty($banco)){
		
		$leitura = read('receber',"WHERE pagamento>='$data1' AND pagamento<='$data2' AND status='Baixado' AND banco='$banco' ORDER BY pagamento ASC");

		$valor_total = soma('receber',"WHERE pagamento>='$data1' AND pagamento<='$data2' 
													AND status='Baixado'AND banco='$banco'",'valor');
		$total = conta('receber',"WHERE pagamento>='$data1' AND pagamento<='$data2' 
													AND status='Baixado' AND banco='$banco'");
	}

	if(!empty($formpag)){
		
		$leitura = read('receber',"WHERE pagamento>='$data1' AND pagamento<='$data2' AND status='Baixado' AND formpag='$formpag' ORDER BY pagamento ASC");

		$valor_total = soma('receber',"WHERE pagamento>='$data1' AND pagamento<='$data2' 
													AND status='Baixado'AND formpag='$formpag'",'valor');
		$total = conta('receber',"WHERE pagamento>='$data1' AND pagamento<='$data2' 
													AND status='Baixado' AND formpag='$formpag'");
	}
 

	if(!empty($banco) AND !empty($formpag) ){

		$leitura = read('receber',"WHERE pagamento>='$data1' AND pagamento<='$data2' AND status='Baixado' AND banco='$banco' AND formpag='$formpag' ORDER BY pagamento ASC");

		$valor_total = soma('receber',"WHERE pagamento>='$data1' AND pagamento<='$data2' 
													AND status='Baixado' AND banco='$banco' AND formpag='$formpag'",'valor');
		$total = conta('receber',"WHERE pagamento>='$data1' AND pagamento<='$data2' 
													AND status='Baixado' AND banco='$banco' AND formpag='$formpag'");
	}

$nome_arquivo = "relatorio-receita-pagas";

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
	$html .= "<td>Contrato</td>";
	$html .= "<td>Controle</td>";
	$html .= "<td>Nome</td>";
	$html .= "<td>Emissao</td>";
	$html .= "<td>Vencimento</td>";
	$html .= "<td>Pagamento</td>";
	$html .= "<td>Juros</td>";
	$html .= "<td>Desconto</td>";
	$html .= "<td>Valor</td>";
	$html .= "<td>Total</td>";
	$html .= "<td>Banco/Forma Pag</td>";
	$html .= "<td>Nota</td>";

	$html .= "<td>Tipo de Contrato</td>";
	$html .= "<td>status</td>";

	$html .= "<td>Data </td>";
	$html .= "<td>Atendente </td>";
	$html .= "<td>Solucao </td>";

	$html .= "<td>Motivo Suspensao</td>";
	$html .= "<td>Data Suspensao</td>";

 		
$html .= "</tr>";

foreach($leitura as $mostra):

	$html .= "<tr>";

		$html .= "<td>".$mostra['id']."</td>";
		
		$contratoId = $mostra['id_contrato'];
		$contrato = mostra('contrato',"WHERE id ='$contratoId'");

		$html .= "<td>".$contrato['id']."</td>";

		$html .= "<td>".substr($contrato['controle'],0,6)."</td>";

		$clienteId = $mostra['id_cliente'];
		$cliente = mostra('cliente',"WHERE id ='$clienteId '");
		$html .= "<td>".$cliente['nome']."</td>";

		$html .= "<td>".converteData($mostra['emissao'])."</td>";
		$html .= "<td>".converteData($mostra['vencimento'])."</td>";
		$html .= "<td>".converteData($mostra['pagamento'])."</td>";

		$html .= "<td>".converteValor($mostra['juros'])."</td>";
		$html .= "<td>".converteValor($mostra['desconto'])."</td>";
		$html .= "<td>".converteValor($mostra['valor'])."</td>";
		$total=$mostra['valor']+$mostra['juros']-$mostra['desconto'];

		$html .= "<td>".converteValor($total)."</td>";

		$bancoId=$mostra['banco'];
		$banco = mostra('banco',"WHERE id ='$bancoId'");
		$formpagId=$mostra['formpag'];
		$formapag = mostra('formpag',"WHERE id ='$formpagId'");

		$html .= "<td>".$banco['nome']. "|".substr($formapag['nome'],0,10)."</td>";

		$html .= "<td>".$mostra['nota']."</td>";

		$tipoId = $contrato['contrato_tipo'];
		$tipo = mostra('contrato_tipo',"WHERE id ='$tipoId'");
		$html .= "<td>". $tipo['nome']."</td>";

		$statusId=$contrato['status'];
		$status = mostra('contrato_status',"WHERE id ='$statusId'");
		$html .= "<td>".$status['nome']."</td>";

		$pedido = mostra('pedido',"WHERE id_contrato ='$contratoId' ORDER BY id ASC");
		if($pedido){
			$html .= "<td>" . converteData($pedido['data_solucao']) ."</td>";
			
			$html .= "<td>".$pedido['atendente_fechamento']."</td>";
			$html .= "<td>".$pedido['solucao']."</td>";
		}

	 	if($contrato['status']==6){

			$contratoBaixa = mostra('contrato_baixa',"WHERE id_contrato ='$contratoId' ORDER BY data ASC");
			$html .= "<td>". substr($contratoBaixa['motivo'],0,30) ."</td>";
			$html .= "<td>". converteData($contratoBaixa['data']) ."</td>";
		}else{
			$html .= "<td>-</td>";
			$html .= "<td>-</td>";
		}


	$html .= "</tr>";
endforeach;

echo $html;

?>