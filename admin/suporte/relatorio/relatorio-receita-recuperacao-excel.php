<?php

ob_start();
session_start();

require_once('../../../config/crud.php');
require_once('../../../config/funcoes.php');

$data1 = $_SESSION['inicio'];
$data2 = $_SESSION['fim'];

$valor_total = soma('receber',"WHERE recuperacao_credito_data>='$data1' AND recuperacao_credito_data<='$data2' AND recuperacao_credito='1'",'valor');
$total = conta('receber',"WHERE recuperacao_credito_data>='$data1' AND recuperacao_credito_data<='$data2' AND recuperacao_credito='1'");
$leitura = read('receber',"WHERE recuperacao_credito_data>='$data1' AND recuperacao_credito_data<='$data2' AND recuperacao_credito='1' ORDER BY recuperacao_credito_data ASC");

if(!empty($statusId )){
	$valor_total = 0;
	$total = 0;
	$status = mostra('contrato_status',"WHERE id='$statusId'");
	
}

$nome_arquivo = "relatorio-recuperacao-credito";
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
	$html .= "<td>Emissao</td>";
	$html .= "<td>Vencimento</td>";
	$html .= "<td>Pagamento</td>";
	$html .= "<td>Juros</td>";
	$html .= "<td>Desconto</td>";
	$html .= "<td>Valor</td>";
	$html .= "<td>Banco/Forma Pag</td>";
	$html .= "<td>Nota</td>";
	$html .= "<td>Retorno</td>";
	$html .= "<td>Boleto Impresso</td>";
$html .= "</tr>";
foreach($leitura as $mostra):

	$contratoId = $mostra['id_contrato'];
	$contrato = mostra('contrato',"WHERE id ='$contratoId'");

	$listar='SIM';
	if(!empty($statusId)){
		if($contrato['status']==$statusId){
			$listar='SIM';
			$valor_total += $mostra['valor'];
			$total++;
		}else{
			$listar='NAO';
		}
	}
		
	if($listar=='SIM'){
		$html .= "<tr>";

			$html .= "<td>".$mostra['id']."</td>";

			$contratoId = $mostra['id_contrato'];
			$contrato = mostra('contrato',"WHERE id ='$contratoId '");

			$html .= "<td>".$contrato['controle']."</td>";

			$clienteId = $mostra['id_cliente'];
			$cliente = mostra('cliente',"WHERE id ='$clienteId '");
			$html .= "<td>".$cliente['nome']."</td>";

			$html .= "<td>".converteData($mostra['emissao'])."</td>";
			$html .= "<td>".converteData($mostra['vencimento'])."</td>";
			$html .= "<td>".converteData($mostra['pagamento'])."</td>";

			$html .= "<td>".converteValor($mostra['juros'])."</td>";
			$html .= "<td>".converteValor($mostra['desconto'])."</td>";
			$html .= "<td>".converteValor($mostra['valor'])."</td>";

			$bancoId=$mostra['banco'];
			$banco = mostra('banco',"WHERE id ='$bancoId'");
			$formpagId=$mostra['formpag'];
			$formapag = mostra('formpag',"WHERE id ='$formpagId'");

			$html .= "<td>".$banco['nome']. "|".$formapag['nome']."</td>";
			$html .= "<td>".$mostra['nota_fiscal']."</td>";
			$html .= "<td>".$mostra['retorno']."</td>";
			$html .= "<td>".date('d/m/Y H:i:s',strtotime($mostra['imprimir']))."</td>";
		
		$html .= "</tr>";
	}

endforeach;


echo $html;

?>