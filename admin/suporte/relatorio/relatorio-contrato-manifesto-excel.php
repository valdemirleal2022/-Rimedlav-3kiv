<?php

ob_start();
session_start();

require_once('../../../config/crud.php');
require_once('../../../config/funcoes.php');


$manifesto = $_SESSION['manifesto'];
$contratoTipo = $_SESSION['contratoTipo'];


$valor_total = soma('contrato',"WHERE id AND tipo='2' AND status='5' AND manifesto='$manifesto'",'valor_mensal');
$total = conta('contrato',"WHERE id AND tipo='2' AND status='5' AND manifesto='$manifesto'");
$leitura = read('contrato',"WHERE id AND tipo='2' AND status='5' AND manifesto='$manifesto' ORDER BY inicio DESC");

if(!empty($contratoTipo)){
	$total = conta('contrato',"WHERE id AND tipo='2' AND status='5' AND manifesto='$manifesto' AND contrato_tipo='$contratoTipo'");
	$leitura = read('contrato',"WHERE id AND tipo='2' AND status='5' AND manifesto='$manifesto' AND contrato_tipo='$contratoTipo' ORDER BY controle ASC");
	$tipocontrato = mostra('contrato_tipo',"WHERE id AND id='$contratoTipo'");
}


$nome_arquivo = "relatorio-contrato-manifesto";
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
	$html .= "<td>Bairro</td>";
	$html .= "<td>CNPJ</td>";
	$html .= "<td>Email</td>";
	$html .= "<td>Tipo</td>";
	$html .= "<td>Coleta</td>";
	$html .= "<td>Valor</td>";
	$html .= "<td>Aprovacao</td>";
	$html .= "<td>Inicio</td>";
$html .= "</tr>";
foreach($leitura as $contrato):
	$html .= "<tr>";
		$html .= "<td>".$contrato['id']."</td>";
		$html .= "<td>". substr($contrato['controle'],0,6)."</td>";
		$clienteId = $contrato['id_cliente'];
		$cliente = mostra('cliente',"WHERE id ='$clienteId '");
		$html .= "<td>".$cliente['nome']."</td>";
		$html .= "<td>".$cliente['bairro']."</td>";
		$html .= "<td>".$cliente['cnpj']."</td>";
		$html .= "<td>".$cliente['email']."</td>";
		$tipoId = $contrato['contrato_tipo'];
		$tipo = mostra('contrato_tipo',"WHERE id ='$tipoId'");
		$html .= "<td>".$tipo['nome']."</td>";

		$contratoId = $contrato['id'];
		$contratoColeta = mostra('contrato_coleta',"WHERE id_contrato ='$contratoId'");
		$tipoColetaId = $contratoColeta['tipo_coleta'];
		$coleta = mostra('contrato_tipo_coleta',"WHERE id ='$tipoColetaId'");
		$html .= "<td>".$coleta['nome']."</td>";

		$html .= "<td>".converteValor($contrato['valor_mensal'])."</td>";

		$html .= "<td>".converteData($contrato['aprovacao'])."</td>";
		$html .= "<td>".converteData($contrato['inicio'])."</td>";

	$html .= "</tr>";
endforeach;

echo $html;

?>