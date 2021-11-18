<?php

ob_start();
session_start();

require_once('../../../config/crud.php');
require_once('../../../config/funcoes.php');

$data1 = $_SESSION['dataInicio'];
$data2 = $_SESSION['dataFinal'];
$rota =$_SESSION[ 'rotaColeta' ];

$total = conta('contrato_ordem',"WHERE data>='$data1' AND data<='$data2'");
$leitura = read('contrato_ordem',"WHERE data>='$data1' AND data<='$data2' ORDER BY hora ASC");

if(!empty($rota)){
	$rotaColeta = mostra('contrato_rota',"WHERE id ='$rota'");
	$total = conta('contrato_ordem',"WHERE data>='$data1' AND data<='$data2' AND rota='$rota'");
	$leitura = read('contrato_ordem',"WHERE data>='$data1' AND data<='$data2' AND rota='$rota' ORDER BY data ASC, hora ASC");
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
	$html .= "<td>CNPJ/CPF</td>";
	$html .= "<td>Contrato/Controle</td>";
	$html .= "<td>Numero</td>";
	$html .= "<td>Hora Chegada</td>";
	$html .= "<td>Manifesto</td>";
	$html .= "<td>Rota</td>";
	$html .= "<td>Data</td>";
	$html .= "<td>Hora da Coleta</td>";
	$html .= "<td>Tipo de Coleta</td>";
	$html .= "<td>Previsto</td>";
	$html .= "<td>Coletado</td>";
$html .= "</tr>";

foreach($leitura as $mostra):

	$html .= "<tr>";

		$html .= "<td>".$mostra['hora']."</td>";

		$contratoId = $mostra['id_contrato'];
		$contrato = mostra('contrato',"WHERE id ='$contratoId'");

		$clienteId = $mostra['id_cliente'];
		$cliente = mostra('cliente',"WHERE id ='$clienteId '");

		$html .= "<td>".$cliente['nome']."</td>";
		$endereco=substr($cliente['endereco'],0,50).','.$cliente['numero'].' - '.$cliente['complemento'];
		$html .= "<td>".$endereco."</td>";
		$html .= "<td>".$cliente['bairro']."</td>";
		$html .= "<td>".$cliente['cnpj'].' '.$cliente['cpf']."</td>";

		$html .= "<td>".$mostra['id_contrato'].'|'.substr($contrato['controle'],0,6)."</td>";

		$html .= "<td>".$mostra['id']."</td>";
		$html .= "<td>_________________</td>";
		$html .= "<td>".$mostra['manifesto']."</td>";

		$rotaId = $mostra['rota'];
		$rota = mostra('contrato_rota',"WHERE id ='$rotaId'");

		$html .= "<td>".$rota['nome']."</td>";
		$html .= "<td>".converteData($mostra['data'])."</td>";
		$html .= "<td>".$mostra['hora_coleta']."</td>";

		$contratoId = $mostra['id_contrato'];
		$tipoColetaId = $mostra['tipo_coleta1'];
        $coletaPrevisto = mostra('contrato_coleta',"WHERE id_contrato ='$contratoId'");

        $coleta = mostra('contrato_tipo_coleta',"WHERE id ='$tipoColetaId'");
        $coletaPrevisto = mostra('contrato_coleta',"WHERE id_contrato ='$contratoId'");
				
		$html .= "<td>".$coleta['nome']."</td>";

		$html .= "<td>".$coletaPrevisto['quantidade']."</td>";
		$html .= "<td>".$mostra['quantidade1']."</td>";
		

	$html .= "</tr>";
endforeach;

echo $html;

?>