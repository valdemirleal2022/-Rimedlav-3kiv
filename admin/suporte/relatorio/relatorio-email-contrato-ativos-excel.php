<?php

ob_start();
session_start();

require_once('../../../config/crud.php');
require_once('../../../config/funcoes.php');

$contratoTipo = $_SESSION['contratoTipo'];

$total = conta('contrato',"WHERE id AND tipo='2' AND status='5'");
$leitura = read('contrato',"WHERE id AND tipo='2' AND status='5' ORDER BY inicio ASC");

if (!empty($contratoTipo)) {
	$total = conta('contrato',"WHERE id AND tipo='2' AND contrato_tipo='$contratoTipo' AND status='5'");
	$leitura = read('contrato',"WHERE id AND tipo='2' AND contrato_tipo='$contratoTipo' AND status='5' ORDER BY inicio ASC");
}

$nome_arquivo = "contratos-email-ativos";
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
	$html .= "<td>Email</td>";
	$html .= "<td>Email - Financeiro</td>";
$html .= "</tr>";
foreach($leitura as $mostra):

	$html .= "<tr>";

		$html .= "<td>".$mostra['id']."</td>";
		$clienteId = $mostra['id_cliente'];
		$cliente = mostra('cliente',"WHERE id ='$clienteId '");

		$html .= "<td>".$cliente['nome']."</td>";
		$html .= "<td>".$cliente['email']."</td>";
		$html .= "<td>".$cliente['email_financeiro']."</td>";

	$html .= "</tr>";

endforeach;

echo $html;

?>