<?php

ob_start();
session_start();

require_once('../../../config/crud.php');
require_once('../../../config/funcoes.php');

$data1 = $_SESSION['inicio'];
$data2 = $_SESSION['fim'];

$leitura = read('receber',"WHERE vencimento>='$data1' AND vencimento<='$data2' AND status='Em Aberto' ORDER BY vencimento ASC");

$nome_arquivo = "relatorio-receita-vencimento";
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

	$html .= "<td>Endereço</td>";
	$html .= "<td>Bairro</td>";
	$html .= "<td>Telefone</td>"; 

	$html .= "<td>Emissao</td>";
	$html .= "<td>Vencimento</td>";
	$html .= "<td>Valor</td>";
	$html .= "<td>Banco/Forma Pag</td>";
	$html .= "<td>Nota</td>";
	$html .= "<td>Consultor</td>";
	$html .= "<td>Boleto Impresso</td>";
	$html .= "<td>Tipo de Contrato</td>";
	$html .= "<td>Email</td>";
	$html .= "<td>Email Financeiro</td>";

	$html .= "<td>Data </td>";
	$html .= "<td>Atendente </td>";
	$html .= "<td>Solucao </td>";

$html .= "</tr>";

foreach($leitura as $mostra):

	$html .= "<tr>";
		$html .= "<td>".$mostra['id']."</td>";
		
		$contratoId = $mostra['id_contrato'];
		$contrato = mostra('contrato',"WHERE id ='$contratoId '");

		$html .= "<td>".substr($contrato['controle'],0,6)."</td>";

		$clienteId = $mostra['id_cliente'];
		$cliente = mostra('cliente',"WHERE id ='$clienteId '");
		$html .= "<td>".$cliente['nome']."</td>";

		$endereco=$cliente['endereco'].', '.$cliente['numero'].' '.$cliente['complemento'];

		$html .= "<td>".$endereco."</td>";

		$html .= "<td>".$cliente['bairro']."</td>";
		$html .= "<td>".$cliente['telefone']."</td>";

		$html .= "<td>".converteData($mostra['emissao'])."</td>";
		$html .= "<td>".converteData($mostra['vencimento'])."</td>";
		$html .= "<td>".converteValor($mostra['valor'])."</td>";

		$bancoId=$mostra['banco'];
		$banco = mostra('banco',"WHERE id ='$bancoId'");
		$formpagId=$mostra['formpag'];
		$formapag = mostra('formpag',"WHERE id ='$formpagId'");

		$html .= "<td>".$banco['nome']. "|".$formapag['nome']."</td>";
		$html .= "<td>".$mostra['nota']."</td>";

		$consultorId = $contrato['consultor'];
		$consultor = mostra('contrato_consultor',"WHERE id ='$consultorId'");
		$html .= "<td>".$consultor['nome']."</td>";
		

		if(empty($mostra['imprimir'])){
			$html .= "<td>-</td>";
		}else{
			$html .= "<td>".  date('d/m/Y H:i:s',strtotime($mostra['imprimir'])) . "</td>";
		}

		$tipoId = $contrato['contrato_tipo'];
		$tipo = mostra('contrato_tipo',"WHERE id ='$tipoId'");
		$html .= "<td>". $tipo['nome']."</td>";
		$html .= "<td>".$cliente['email']."</td>";
		$html .= "<td>".$cliente['emial_financeiro']."</td>";

		$pedido = mostra('pedido',"WHERE id_contrato ='$contratoId' ORDER BY id ASC");
		if($pedido){
			$html .= "<td>" . converteData($pedido['data_solucao']) ."</td>";
			
			$html .= "<td>".$pedido['atendente_fechamento']."</td>";
			$html .= "<td>".$pedido['solucao']."</td>";
		}


	$html .= "</tr>";
endforeach;

echo $html;

?>