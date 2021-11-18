<?php

ob_start();
session_start();

require_once('../../../config/crud.php');
require_once('../../../config/funcoes.php');

$data1 = $_SESSION['inicio'];
$data2 = $_SESSION['fim'];
$conta = $_SESSION['conta'];

$total = conta('pagar',"WHERE id AND vencimento>='$data1' AND vencimento<='$data2' AND status<>'Baixado'");
$valor_total = soma('pagar',"WHERE id AND vencimento>='$data1' AND vencimento<='$data2' AND status<>'Baixado'",'valor');
$leitura = read('pagar',"WHERE id AND vencimento>='$data1' AND vencimento<='$data2' AND status<>'Baixado' ORDER BY vencimento ASC");

if( !empty($conta) ){
	$total = conta('pagar',"WHERE  id_conta='$conta' AND status='Em Aberto' AND vencimento>='$data1' AND vencimento<='$data2'");
	$valor_total = soma('pagar',"WHERE id_conta='$conta' AND status='Em Aberto' AND vencimento>='$data1' AND vencimento<='$data2'",'valor');
	$leitura = read('pagar',"WHERE  id_conta='$conta' AND status='Em Aberto' AND vencimento>='$data1' AND vencimento<='$data2' ORDER BY vencimento ASC");
}

$nome_arquivo = "relatorio-despesas";
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
	$html .= "<td>Fornecedor</td>";
	$html .= "<td>Emissao</td>";
	$html .= "<td>Vencimento</td>";
	$html .= "<td>Programacao</td>";
	$html .= "<td>Valor</td>";
	$html .= "<td>Parcela</td>";
	$html .= "<td>Banco/Form Pag</td>";

	$html .= "<td>Descricao</td>";

	$html .= "<td>Centro de Custo</td>";
	$html .= "<td>Categoria</td>";
	
	$html .= "<td>Empresa</td>";
	$html .= "<td>Funcionario</td>";

$html .= "</tr>";

foreach($leitura as $mostra):

	$html .= "<tr>";

		$html .= "<td>".$mostra['id']."</td>";

		$fornecedorId = $mostra['fornecedor'];
		$fornecedor = mostra('estoque_fornecedor',"WHERE id ='$fornecedorId '");
		
		$html .= "<td>".$fornecedor['nome']."</td>";

		$html .= "<td>".converteData($mostra['emissao'])."</td>";
		$html .= "<td>".converteData($mostra['vencimento'])."</td>";
		$html .= "<td>".converteData($mostra['programacao'])."</td>";
		$html .= "<td>".converteValor($mostra['valor'])."</td>";

		$html .= "<td>".$mostra['parcela']."</td>";

		$bancoId=$mostra['banco'];
		$banco = mostra('banco',"WHERE id ='$bancoId'");
		$formpagId=$mostra['formpag'];
		$formapag = mostra('formpag',"WHERE id ='$formpagId'");

		$html .= "<td>".$banco['nome']. "|".$formapag['nome']."</td>";

		$html .= "<td>".$mostra['descricao']."</td>";

		$contaId = $mostra['id_conta'];
		$conta = mostra('pagar_conta',"WHERE id ='$contaId'");

		$html .= "<td>".$conta['nome']."</td>";

		$grupoId = $mostra['id_grupo'];
		$grupo = mostra('pagar_grupo',"WHERE id ='$grupoId'");

		$html .= "<td>".$grupo['nome']."</td>";

		$empresaId = $mostra['empresa'];
		$empresa = mostra('empresa_pagar',"WHERE id ='$empresaId '");
		
		$html .= "<td>".$empresa['nome']."</td>";


		$funcionarioId = $mostra['funcionario'];
		$funcionario = mostra('funcionario',"WHERE id ='$funcionarioId '");
		
		$html .= "<td>".$funcionario['nome']."</td>";

	$html .= "</tr>";
endforeach;

echo $html;

?>