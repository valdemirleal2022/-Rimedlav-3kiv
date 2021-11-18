<?php

ob_start();
session_start();

require_once('../../../config/crud.php');
require_once('../../../config/funcoes.php');

$consultor = $_SESSION['consultor'];

$valor_total = soma('contrato',"WHERE id AND tipo='2' AND status<>'9' AND consultor='$consultor' AND status<>'9'",'valor_mensal');
$total = conta('contrato',"WHERE id AND tipo='2' AND consultor='$consultor' AND status<>'9'");
$leitura = read('contrato',"WHERE id AND tipo='2' AND consultor='$consultor' AND status<>'9'  ORDER BY inicio ASC");

$nome_arquivo = "contratos-consultor";

if(empty($consultor)){
	$valor_total = soma('contrato',"WHERE id AND tipo='2' AND status<>'9'",'valor_mensal');
	$total = conta('contrato',"WHERE id AND tipo='2' AND  AND status<>'9'");
	$leitura = read('contrato',"WHERE id AND tipo='2' AND AND status<>'9'  ORDER BY inicio ASC");
	$nome_arquivo = "contratos-total-valorunitario";
}


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
	$html .= "<td>Endereço</td>";
	$html .= "<td>Bairro</td>";
	$html .= "<td>Telefone</td>";
	$html .= "<td>Email</td>";
	$html .= "<td>Tipo Coleta</td>";
	$html .= "<td>Unitario</td>";
	$html .= "<td>Valor Mensal</td>";
	$html .= "<td>Aprovacao</td>";
	$html .= "<td>Inicio</td>";
	$html .= "<td>Status</td>";
	$html .= "<td>Rota</td>";
$html .= "</tr>";

foreach($leitura as $mostra):

	$html .= "<tr>";
		$html .= "<td>".$mostra['id']."</td>";

		$clienteId = $mostra['id_cliente'];
		$cliente = mostra('cliente',"WHERE id ='$clienteId '");

		$html .= "<td>".$cliente['nome']."</td>";

		$endereco=$cliente['endereco'].', '.$cliente['numero'].' '.$cliente['complemento'];

		$html .= "<td>".$endereco."</td>";

		$html .= "<td>".$cliente['bairro']."</td>";
		$html .= "<td>".$cliente['telefone']."</td>";
		$html .= "<td>".$cliente['email']."</td>";
		
		$contratoId = $mostra['id'];
		$contratoColeta = mostra('contrato_coleta',"WHERE id_contrato ='$contratoId' ORDER BY id DESC");
		$tipoColetaId = $contratoColeta['tipo_coleta'];
		$coleta = mostra('contrato_tipo_coleta',"WHERE id ='$tipoColetaId'");

		$html .= "<td>".$coleta['nome']."</td>";
		$html .= "<td>".converteValor($contratoColeta['valor_unitario'])."</td>";

		$html .= "<td>".converteValor($contratoColeta['valor_mensal'])."</td>";

		$html .= "<td>".converteData($mostra['aprovacao'])."</td>";
		$html .= "<td>".converteData($mostra['inicio'])."</td>";
			
		$statusId=$mostra['status'];
		$status = mostra('contrato_status',"WHERE id ='$statusId'");
		$html .= "<td>".$status['nome']."</td>";
	$html .= "</tr>";
endforeach;

echo $html;

?>