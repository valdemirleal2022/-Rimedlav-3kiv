<?php

ob_start();
session_start();

require_once('../../../config/crud.php');
require_once('../../../config/funcoes.php');

$data1 = $_SESSION['dataInicio'];
$data2 = $_SESSION['dataFinal'];
$rotaRoteiro = $_SESSION['rotaColeta'];

$total = conta( 'contrato_ordem', "WHERE id AND data>='$data1' AND data<='$data2' AND quantidade1='0'" );
$leitura = read( 'contrato_ordem', "WHERE id AND data>='$data1' AND data<='$data2' AND quantidade1='0' ORDER BY data ASC" );

if(!empty($rotaRoteiro)){
	$total = conta( 'contrato_ordem', "WHERE id AND data>='$data1' AND data<='$data2' AND rota<='$rotaRoteiro' AND quantidade1='0'" );
	$leitura = read( 'contrato_ordem', "WHERE id AND data>='$data1' AND data<='$data2' AND rota<='$rotaRoteiro' AND quantidade1='0' ORDER BY data ASC" );
}


$nome_arquivo = "relatorio-ordem-zerada";
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
	$html .= "<td>Tipo Contrato</td>";
	$html .= "<td>Coleta</td>";
	$html .= "<td>Rota</td>";
	$html .= "<td>Data</td>";
	$html .= "<td>Observacao</td>";
	$html .= "<td>Motivo</td>";
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

		$contratoTipoId = $contrato['contrato_tipo'];
		$contratoTipo = mostra('contrato_tipo',"WHERE id ='$contratoTipoId'");

		$html .= "<td>".$contratoTipo['nome']."</td>";

		$rotaId = $mostra['rota'];
    	$rota = mostra('contrato_rota',"WHERE id ='$rotaId'");

		$html .= "<td>".$rota['nome']."</td>";
		$html .= "<td>".converteData($mostra['data'])."</td>";

		$html .= "<td>". $mostra['observacao'] ."</td>";

		$motivoId = $mostra['motivo_nao_coletado'];
    	$motivo = mostra('ordem_motivo_naocoletado',"WHERE id ='$motivoId'");
 
		$html .= "<td>".$motivo['nome']."</td>";



	$html .= "</tr>";
endforeach;

echo $html;

?>