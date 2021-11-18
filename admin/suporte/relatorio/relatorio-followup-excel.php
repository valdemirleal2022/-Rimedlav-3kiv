<?php

ob_start();
session_start();

require_once('../../../config/crud.php');
require_once('../../../config/funcoes.php');

$data1 = $_SESSION['inicio'];
$data2 = $_SESSION['fim'];
	
		$total = conta('cadastro_visita',"WHERE id AND status='3' AND orc_data>='$data1' AND orc_data<='$data2'");
		
		$leitura = read('cadastro_visita',"WHERE id AND status='3' AND orc_data>='$data1' AND orc_data<='$data2' ORDER BY orc_data ASC, orc_hora ASC");
		
		$valor_total = soma('cadastro_visita',"WHERE id AND status='3' AND orc_data>='$data1' AND orc_data<='$data2'",'orc_valor');
			
		
		if(!empty($consultorId)){
			
			$total = conta('cadastro_visita',"WHERE id AND status='3' AND orc_data>='$data1' AND orc_data<='$data2' AND consultor='$consultorId'");
			
			$valor_total = soma('cadastro_visita',"WHERE id AND status='3' AND orc_data>='$data1' AND orc_data<='$data2' AND consultor='$consultorId'",'orc_valor');
			
			$leitura = read('cadastro_visita',"WHERE id AND status='3' AND orc_data>='$data1' AND orc_data<='$data2' AND consultor='$consultorId' ORDER BY orc_data ASC, orc_hora ASC");
		}

$nome_arquivo = "relatorio-followup";
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
	$html .= "<td>Endereco</td>";
	$html .= "<td>Bairro</td>";
	$html .= "<td>Telefone</td>";
	$html .= "<td>Contato</td>";
	$html .= "<td>Data</td>";
	$html .= "<td>Consultor</td>";
	$html .= "<td>Valor</td>";
$html .= "</tr>";
foreach($leitura as $mostra):

		$html .= "<tr>";
			$html .= "<td>".$mostra['id']."</td>";
			$html .= "<td>".$mostra['nome']."</td>";

			$endereco=$mostra['endereco'].', '.$mostra['numero'].' '.$mostra['complemento'];

			$html .= "<td>".$endereco."</td>";

			$html .= "<td>".$mostra['bairro']."</td>";
			$html .= "<td>".$mostra['telefone']."</td>";
			$html .= "<td>".$mostra['contato']."</td>";

			$html .= "<td>".converteData($mostra['orc_data'])."</td>";
			$consultorId=$mostra['consultor'];
			$consultor = mostra('contrato_consultor',"WHERE id ='$consultorId'");
			$html .= "<td>".$consultor['nome']."</td>";
			
			
			
			$html .= "<td>".converteValor($mostra['orc_valor'])."</td>";
			
			$total++;
		$html .= "</tr>";
	
endforeach;


echo $html;

?>