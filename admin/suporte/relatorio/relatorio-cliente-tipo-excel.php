<?php

ob_start();
session_start();

require_once('../../../config/crud.php');
require_once('../../../config/funcoes.php');

$clienteTipo = $_SESSION['clienteTipo'];
$clienteClassificacao = $_SESSION['clienteClassificacao'];


$leitura = read('cliente',"WHERE id ORDER BY nome ASC");
$total =  conta('cliente',"WHERE id");

if(!empty($clienteTipo)){
			$leitura = read('cliente',"WHERE id AND tipo='$clienteTipo' ORDER BY nome ASC");
			$total =  conta('cliente',"WHERE id AND tipo='$clienteTipo'");
		}
		if(!empty($clienteClassificacao)){
			$leitura = read('cliente',"WHERE id AND classificacao='$clienteClassificacao' ORDER BY nome ASC");
			$total =  conta('cliente',"WHERE id AND classificacao='$clienteClassificacao'");
		}
		
		if(!empty($clienteClassificacao) and !empty($clienteTipo) ){
			$leitura = read('cliente',"WHERE id AND tipo='$clienteTipo' AND classificacao='$clienteClassificacao' ORDER BY nome ASC");
			$total =  conta('cliente',"WHERE id AND tipo='$clienteTipo' AND classificacao='$clienteClassificacao'");
}


$nome_arquivo = "relatorio-cliente-tipo";
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
	$html .= "<td>Bairro</td>";
	$html .= "<td>Telefone</td>";
	$html .= "<td>Contato</td>";
	$html .= "<td>Email|Email Financeiro</td>";
	$html .= "<td>Classificacao</td>";
$html .= "</tr>";
foreach($leitura as $mostra):

	
		$html .= "<tr>";
			 
			$html .= "<td>".$mostra['nome']."</td>";

			$endereco=substr($mostra['endereco'],0,50).','.$mostra['numero'].' - '.$mostra['complemento'];
			$html .= "<td>".$endereco."</td>";	

			$html .= "<td>".$mostra['bairro']."</td>";
			$html .= "<td>".$mostra['telefone']."</td>";
			$html .= "<td>".$mostra['contato']."</td>";

			$html .= "<td>". $mostra['email'].'|'.$mostra['email_financeiro']."</td>";
	
			$classificacaoId=$mostra['classificacao'];
			$classificacao = mostra('cliente_classificacao',"WHERE id='$classificacaoId'");
	
			$html .= "<td>".$classificacao['nome']."</td>";
	
		$html .= "</tr>";
	
endforeach;


$html .= "<tr>";
	$html .= "<td>Total</td>";
	$html .= "<td>". $total."</td>";
$html .= "</tr>";


echo $html;

?>