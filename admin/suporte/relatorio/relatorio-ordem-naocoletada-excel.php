<?php

ob_start();
session_start();

require_once('../../../config/crud.php');
require_once('../../../config/funcoes.php');

$data1 = $_SESSION['dataInicio'];
$data2 = $_SESSION['dataFinal'];

$total = conta('contrato_ordem',"WHERE data>='$data1' AND data<='$data2' 
														AND nao_coletada='1'");
$leitura = read('contrato_ordem',"WHERE data>='$data1' AND data<='$data2' 
														AND nao_coletada='1' ORDER BY data ASC");

$nome_arquivo = "relatorio-ordem-naocoletada-excel";
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
	$html .= "<td>Telefone</td>";
	$html .= "<td>Residuo</td>";
	$html .= "<td>Rota</td>";
	$html .= "<td>Data</td>";
	$html .= "<td>Observacao</td>";
	$html .= "<td>Valor Previsto</td>";
$html .= "</tr>";

foreach($leitura as $mostra):
	$html .= "<tr>";

		$html .= "<td>".$mostra['id']."</td>";

		$contratoId = $mostra['id_contrato'];
    	$contrato = mostra('contrato',"WHERE id ='$contratoId'");
		$html .= "<td>".$contrato['controle']."</td>";

		$clienteId = $mostra['id_cliente'];
		$cliente = mostra('cliente',"WHERE id ='$clienteId '");
		$html .= "<td>".$cliente['nome']."</td>";

		$html .= "<td>".$cliente['bairro']."</td>";
		$html .= "<td>".$cliente['telefone']."</td>";

		$tipoColetaId = $mostra['tipo_coleta1'];
		$coleta = mostra('contrato_tipo_coleta',"WHERE id ='$tipoColetaId'");

		$residuoId = $coleta['residuo'];
		$residuo = mostra('contrato_tipo_residuo',"WHERE id ='$residuoId'");

		$html .= "<td>".$residuo['nome']."</td>";

		$rotaId = $mostra['rota'];
    	$rota = mostra('contrato_rota',"WHERE id ='$rotaId'");

		$html .= "<td>".$rota['nome']."</td>";
		$html .= "<td>".converteData($mostra['data'])."</td>";

		$html .= "<td>". $mostra['observacao'] ."</td>";

		$tipoColetaId = $mostra['tipo_coleta1'];
    	$coleta = mostra('contrato_tipo_coleta',"WHERE id ='$tipoColetaId'");

		$contratoId = $mostra['id_contrato'];
		$dataroteiro=$mostra['data'];
		$contratoColeta = mostra( 'contrato_coleta', "WHERE id AND inicio<='$dataroteiro' AND vencimento>='$dataroteiro' AND id_contrato='$contratoId'  AND tipo_coleta='$tipoColetaId'" );

		$valor=$contratoId = $contratoColeta['quantidade']*$contratoColeta['valor_unitario'];

		$html .= "<td>". converteValor($valor) ."</td>";



	$html .= "</tr>";
endforeach;

echo $html;

?>