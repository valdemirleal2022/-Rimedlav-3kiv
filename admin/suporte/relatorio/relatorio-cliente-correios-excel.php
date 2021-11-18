<?php

ob_start();
session_start();

require_once('../../../config/crud.php');
require_once('../../../config/funcoes.php');

$total=0;
$leitura = read('contrato',"WHERE id AND enviar_boleto_correio='1' ORDER BY inicio ASC");

$nome_arquivo = "relatorio-cliente-correios";
header("Content-type: application/vnd.ms-excel");
header("Content-type: application/force-download");
header("Content-Disposition: attachment; filename=$nome_arquivo.xls");
header("Pragma: no-cache");
 
$html = '';
$html .= "<body>";
$html .= "<table>";
$html .= "<tbody>";
$html .= "<tr>";
	$html .= "<td>Nome</td>";
	$html .= "<td>Endereco</td>";
	$html .= "<td>Nome</td>";
	$html .= "<td>Bairro</td>";
	$html .= "<td>Telefone</td>";
	$html .= "<td>Telefone</td>";
	$html .= "<td>Contrato</td>";
	$html .= "<td>Consultor</td>";
$html .= "</tr>";
foreach($leitura as $mostra):

	$clienteId = $mostra['id_cliente'];
	$cliente = mostra('cliente',"WHERE id ='$clienteId'");
		
		$html .= "<tr>";
			 
			$html .= "<td>".$cliente['nome']."</td>";
			$endereco=substr($cliente['endereco'],0,50).','.$cliente['numero'].' - '.$cliente['complemento'];
			$html .= "<td>".$endereco."</td>";	
			$html .= "<td>".$cliente['bairro']."</td>";
			$html .= "<td>".$cliente['telefone']."</td>";
			$html .= "<td>".$cliente['telefone']."</td>";

			$html .= "<td>".substr($mostra['controle'],0,6)."</td>";
		
			$consultorId=$contrato['consultor'];
			$consultor = mostra('contrato_consultor',"WHERE id ='$consultorId'");
			$html .= "<td>".$consultor['nome']."</td>";
	
		$html .= "</tr>";
endforeach;


$html .= "<tr>";
	$html .= "<td>Total</td>";
	$html .= "<td>". $total."</td>";
$html .= "</tr>";


echo $html;

?>