<?php

ob_start();
session_start();

require_once('../../../config/crud.php');
require_once('../../../config/funcoes.php');

$data1 = $_SESSION['inicio'];
$data2 = $_SESSION['fim'];
$consultorId = $_SESSION['consultor'];

$total = conta('cadastro_visita',"WHERE id AND data>='$data1' AND data<='$data2'");
$leitura = read('cadastro_visita',"WHERE id AND data>='$data1' AND data<='$data2' ORDER BY data DESC");

$visitas = conta('cadastro_visita',"WHERE id AND status='0' AND data>='$data1' 
AND data<='$data2'");
$orcamento = conta('cadastro_visita',"WHERE id AND status='2' AND data>='$data1' 
AND data<='$data2'");
$propostas = conta('cadastro_visita',"WHERE id AND status='3' AND data>='$data1' 
AND data<='$data2'");
$aprovados = conta('cadastro_visita',"WHERE id AND status='4' AND data>='$data1' 
AND data<='$data2'");
$cancelados = conta('cadastro_visita',"WHERE id AND status='17' AND data>='$data1' 
AND data<='$data2'");
		
if(!empty($consultorId)){
	
	$total = conta('cadastro_visita',"WHERE id AND data>='$data1' AND data<='$data2' AND consultor='$consultorId'");
	$leitura = read('cadastro_visita',"WHERE id AND data>='$data1' AND data<='$data2' AND
	consultor='$consultorId' ORDER BY data ASC");
	
	$visitas = conta('cadastro_visita',"WHERE id AND status='0' AND data>='$data1' AND data<='$data2' AND consultor='$consultorId'");
	$orcamentos = conta('cadastro_visita',"WHERE id AND status='2' AND data>='$data1' AND data<='$data2' AND consultor='$consultorId'");
	$propostas = conta('cadastro_visita',"WHERE id AND status='3' AND data>='$data1' AND data<='$data2'  AND consultor='$consultorId'");
	$aprovados = conta('cadastro_visita',"WHERE id AND status='4' AND data>='$data1' AND data<='$data2'  AND consultor='$consultorId'");
	$cancelados = conta('cadastro_visita',"WHERE id AND status='17' AND data>='$data1' AND data<='$data2'  AND consultor='$consultorId'");
	
}

$nome_arquivo = "relatorio-acompanhamento-visitas-excel";
header("Content-type: application/vnd.ms-excel");
header("Content-type: application/force-download");
header("Content-Disposition: attachment; filename=$nome_arquivo.xls");
header("Pragma: no-cache");

echo '<head> <meta charset="iso-8859-1"></head>';
 
$html = '';
$html .= "<body>";
$html .= "<table>";
$html .= "<tbody>";
$html .= "<tr>";
	$html .= "<td>Id</td>";
	$html .= "<td>Nome</td>";
	$html .= "<td>Endereco</td>";
	$html .= "<td>Bairro</td>";
	$html .= "<td>Telefone</td>";
	$html .= "<td>Data</td>";
	$html .= "<td>Orçamento</td>";
	$html .= "<td>Consultor</td>";
	$html .= "<td>Indicação</td>";
	$html .= "<td>Status</td>";
$html .= "</tr>";

foreach($leitura as $mostra):
	$html .= "<tr>";

		$html .= "<td>".$mostra['id']."</td>";
		$html .= "<td>".$mostra['nome']."</td>";
		$endereco=$mostra['endereco'].', '.$mostra['numero'].' '.$mostra['complemento'];

		$html .= "<td>".$endereco."</td>";
		$html .= "<td>".$mostra['bairro']."</td>";
		$html .= "<td>".$mostra['telefone']."</td>";
		$html .= "<td>". converteData($mostra['data'])."</td>";
		$html .= "<td>". converteData($mostra['orc_data'])."</td>";

		$consultorId=$mostra['consultor'];
		$consultor = mostra('contrato_consultor',"WHERE id ='$consultorId'");
		$html .= "<td>".$consultor['nome']."</td>";

		$indicacaoId=$mostra['indicacao'];
		$indicacao = mostra('contrato_indicacao',"WHERE id ='$indicacaoId'");
		$html .= "<td>".$indicacao['nome']."</td>";

		$statusId=$mostra['status'];
		if($statusId=='0'){
			$html .= "<td>Visita</td>";
		}else{
			$status = mostra('contrato_status',"WHERE id ='$statusId'");
			$html .= "<td>".$status['nome']."</td>";
		}

	$html .= "</tr>";
endforeach;

$html .= "<tr>";
	$html .= "<td></td>";
	$html .= "<td>Total de Visitas :". $visitas."</td>";
$html .= "</tr>";
$html .= "<tr>";
	$html .= "<td></td>";
	$html .= "<td>Total de Orçamentos :". $orcamentos."</td>";
$html .= "</tr>";
$html .= "<tr>";
	$html .= "<td></td>";
	$html .= "<td>Total de Propostas :". $propostas."</td>";
$html .= "</tr>";
$html .= "<tr>";
	$html .= "<td></td>";
	$html .= "<td>Total de Aprovados :". $aprovados."</td>";
$html .= "</tr>";
$html .= "<tr>";
	$html .= "<td></td>";
	$html .= "<td>Total de Cancelados :". $cancelados."</td>";
$html .= "</tr>";
$html .= "<tr>";
	$html .= "<td></td>";
	$html .= "<td>Total de registros :". $total."</td>";
$html .= "</tr>";


echo $html;

?>