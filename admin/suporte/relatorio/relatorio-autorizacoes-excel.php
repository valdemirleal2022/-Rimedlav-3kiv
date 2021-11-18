<?php

ob_start();
session_start();

require_once('../../../config/crud.php');
require_once('../../../config/funcoes.php');

$aprovacao_comercial = $_SESSION['aprovacao_comercial'];

$valor_total = soma('cadastro_visita',"WHERE id AND aprovacao_comercial='2' OR aprovacao_comercial='3'",'orc_valor');
$total = conta('cadastro_visita',"WHERE id AND aprovacao_comercial='2' OR aprovacao_comercial='3'");
$leitura = read('cadastro_visita',"WHERE id AND aprovacao_comercial='2' OR aprovacao_comercial='3' ORDER BY orc_data DESC");

if(!empty($aprovacao_comercial)){
	$valor_total = soma('cadastro_visita',"WHERE id AND aprovacao_comercial='$aprovacao_comercial'",'orc_valor');
 	$total = conta('cadastro_visita',"WHERE id AND aprovacao_comercial='$aprovacao_comercial'");
	$leitura = read('cadastro_visita',"WHERE id AND aprovacao_comercial='$aprovacao_comercial' ORDER BY orc_data DESC");
}
	

$nome_arquivo = "relatorio-autorizacoes";
header("Content-type: application/vnd.ms-excel");
header("Content-type: application/force-download");
header("Content-Disposition: attachment; filename=$nome_arquivo.xls");
header("Pragma: no-cache");
 
$html = '';
$html .= "<body>";
$html .= "<table>";
$html .= "<tbody>";
$html .= "<tr>";
	$html .= "<td> Id </td>";
	$html .= "<td> Nome </td>";
	$html .= "<td> Bairro </td>";
	$html .= "<td> Valor </td>";
	$html .= "<td> Solicitacao </td>";
	$html .= "<td> Tipo de Resíduos </td>";
	$html .= "<td> Vendedor </td>";
	$html .= "<td> Comercial</td>";
	$html .= "<td> Observação Comercial </td>";
	$html .= "<td> Operacional </td>";
	$html .= "<td> Observação Operacional </td>";
$html .= "</tr>";
foreach($leitura as $mostra):

	 
		$html .= "<tr>";
		
			$html .= "<td>".$mostra['id']."</td>";

			$clienteId = $mostra['id_cliente'];
			$cliente = mostra('cliente',"WHERE id ='$clienteId '");

			$html .= "<td>".$cliente['nome']."</td>";
			$html .= "<td>".$cliente['bairro']."</td>";
			$html .= "<td>".converteValor($mostra['valor'])."</td>";
			$html .= "<td>".converteData($mostra['orc_data'])."</td>";
 			$html .= "<td>".$mostra['orc_residuo']."</td>";

			$consultorId=$mostra['consultor'];
			$consultor = mostra('contrato_consultor',"WHERE id ='$consultorId'");
			$html .= "<td>".$consultorId['nome']."</td>";

				if($mostra['aprovacao_comercial']=='1'){
					$liberacaoComercial='Aprovado';
				}
				if($mostra['aprovacao_comercial']=='2'){
					$liberacaoComercial='Não Autorizado';
				}
				if($mostra['aprovacao_comercial']=='3'){
					$liberacaoComercial='Aguardando';
				}
				if($mostra['aprovacao_operacional']=='1'){
					$liberacaoOperacional='Aprovado';
				}
				if($mostra['aprovacao_operacional']=='2'){
					$liberacaoOperacional='Não Autorizado';
				}
				if($mostra['aprovacao_operacional']=='3'){
					$liberacaoOperacional='Aguardando';
				}
	
			$html .= "<td>".$liberacaoComercial."</td>";
			$html .= "<td>".$mostra['aprovacao_comercial_observacao']."</td>";
		
			$html .= "<td>".$liberacaoOperacional."</td>";
			$html .= "<td>".$mostra['aprovacao_operacional_observacao']."</td>";
		 
		 
			$total++;
		
		$html .= "</tr>";
 
endforeach;


echo $html;

?>