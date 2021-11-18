<?php

ob_start();
session_start();

require_once('../../../config/crud.php');
require_once('../../../config/funcoes.php');

$rota='';
$data1 = $_SESSION['dataInicio'];
$data2 = $_SESSION['dataFinal'];

if(isset($_SESSION[ 'rotaColeta' ])){
	$rota =$_SESSION[ 'rotaColeta' ];
}


$total = conta('contrato_ordem',"WHERE data>='$data1' AND data<='$data2'");
$leitura = read('contrato_ordem',"WHERE data>='$data1' AND data<='$data2' ORDER BY hora ASC");

if(!empty($rota)){
	$rota =$_SESSION[ 'rotaColeta' ];
	$rotaColeta = mostra('contrato_rota',"WHERE id ='$rota'");
	$total = conta('contrato_ordem',"WHERE data>='$data1' AND data<='$data2' AND rota='$rota'");
	$leitura = read('contrato_ordem',"WHERE data>='$data1' AND data<='$data2' AND rota='$rota' ORDER BY hora ASC");
}


$nome_arquivo = "relatorio-ordem-agendadas";
header("Content-type: application/vnd.ms-excel");
header("Content-type: application/force-download");
header("Content-Disposition: attachment; filename=$nome_arquivo.xls");
header("Pragma: no-cache");
 
$html = '';
$html .= "<body>";
$html .= "<table>";
$html .= "<tbody>";
$html .= "<tr>";
	$html .= "<td>Hora</td>";
	$html .= "<td>Bairro</td>";
	$html .= "<td>Endereco</td>";
	$html .= "<td>Cliente</td>";
	$html .= "<td>Numero</td>";
	$html .= "<td>Coleta</td>";
	$html .= "<td>Previsto</td>";
	$html .= "<td>Coletado</td>";
	$html .= "<td>Status</td>";
	$html .= "<td>Contrato</td>";
	$html .= "<td>Rota</td>";
$html .= "</tr>";

foreach($leitura as $mostra):

	$html .= "<tr>";
		$html .= "<td>".$mostra['hora']."</td>";
		$clienteId = $mostra['id_cliente'];
		$cliente = mostra('cliente',"WHERE id ='$clienteId '");
		$html .= "<td>".$cliente['bairro']."</td>";
		$html .= "<td>".$cliente['endereco'].' '.$cliente['numero'].' '.$cliente['complemento']."</td>";
		$html .= "<td>".$cliente['nome']."</td>";
		$html .= "<td>".$mostra['id']."</td>";

		$tipoColetaId = $mostra['tipo_coleta1'];
		$coleta = mostra('contrato_tipo_coleta',"WHERE id ='$tipoColetaId'");

		$html .= "<td>".$coleta['nome']."</td>";

		$contratoId = $mostra['id_contrato'];
		$contratoColeta = mostra('contrato_coleta',"WHERE id_contrato ='$contratoId'");
		$html .= "<td>".$contratoColeta['quantidade']."</td>";
		
		$html .= "<td>".$mostra['quantidade1']."</td>";

		$statusId = $mostra['status'];
		$status = mostra('contrato_status',"WHERE id ='$statusId'");
		$html .= "<td>".$status['nome']."</td>";

		$contratoId = $mostra['id_contrato'];
    	$contrato = mostra('contrato',"WHERE id ='$contratoId'");
		
		$html .= "<td>" . $contrato['id_contrato'].'|' . substr($contrato['controle'],0,6) . "</td>";

		$rotaId = $mostra['rota'];
		$rota = mostra('contrato_rota',"WHERE id ='$rotaId'");

		$html .= "<td>".$rota['nome']."</td>";
		

	$html .= "</tr>";
endforeach;

echo $html;

?>