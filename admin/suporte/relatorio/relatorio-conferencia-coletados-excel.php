<?php

ob_start();
session_start();

require_once('../../../config/crud.php');
require_once('../../../config/funcoes.php');

$rota='';
if(isset($_SESSION['dataInicio'])){
	$data1 = $_SESSION['dataInicio'];
}
if(isset($_SESSION['dataFinal'])){
	$data2 = $_SESSION['dataFinal'];
}
if(isset($_SESSION[ 'rotaColeta' ])){
	$rota =$_SESSION[ 'rotaColeta' ];
}

$total = conta('contrato_ordem',"WHERE data>='$data1' AND data<='$data2'");
$leitura = read('contrato_ordem',"WHERE data>='$data1' AND data<='$data2' ORDER BY hora ASC");

if(!empty($rota)){
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
	$html .= "<td>Nome</td>";
	$html .= "<td>Endereco</td>";
	$html .= "<td>Bairro</td>";
	$html .= "<td>Contrato</td>";
	$html .= "<td>Numero</td>";
	$html .= "<td>Hora Chegada</td>";
	$html .= "<td>Manifesto</td>";
	$html .= "<td>Rota</td>";
$html .= "</tr>";

foreach($leitura as $mostra):

	$html .= "<tr>";

		$html .= "<td>".$mostra['hora']."</td>";

		$clienteId = $mostra['id_cliente'];
		$cliente = mostra('cliente',"WHERE id ='$clienteId '");

		$html .= "<td>".$cliente['nome']."</td>";
		$endereco=substr($cliente['endereco'],0,50).','.$cliente['numero'].' - '.$cliente['complemento'];
		$html .= "<td>".$endereco."</td>";
		$html .= "<td>".$cliente['bairro']."</td>";

		$html .= "<td>".$mostra['id_contrato']."</td>";
		$html .= "<td>".$mostra['id']."</td>";
		$html .= "<td>_________________</td>";
		$html .= "<td>".$mostra['manifesto']."</td>";

		$rotaId = $mostra['rota'];
		$rota = mostra('contrato_rota',"WHERE id ='$rotaId'");

		$html .= "<td>".$rota['nome']."</td>";
		

	$html .= "</tr>";
endforeach;

echo $html;

?>