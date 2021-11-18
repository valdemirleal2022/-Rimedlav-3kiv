<?php

ob_start();
session_start();

require_once('../../../config/crud.php');
require_once('../../../config/funcoes.php');

$data1 = $_SESSION['inicio'];
$data2 = $_SESSION['fim'];

$leitura = read( 'cadastro_visita', "WHERE id AND status='17' AND orc_solicitacao>='$data1' AND orc_solicitacao<='$data2' ORDER BY orc_solicitacao ASC" );

$total = conta( 'cadastro_visita', "WHERE id AND status='17' AND orc_solicitacao>='$data1' AND orc_solicitacao<='$data2'" );
$valor_total = soma('cadastro_visita',"WHERE id AND status='17' AND orc_solicitacao>='$data1' AND orc_solicitacao<='$data2'",'orc_valor');
		
$nome_arquivo = "relatorio-orcamento-cancelados-excel";
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
	$html .= "<td>Endereço</td>";
	$html .= "<td>Valor</td>";
	$html .= "<td>Solicitação</td>";
	$html .= "<td>Orçamento/Hora</td>";
	$html .= "<td>Indicacao</td>";
	$html .= "<td>Tipo de Resíduo'</td>";
	$html .= "<td>Vendedor</td>";
	$html .= "<td>Status</td>";
	$html .= "<td>Empresa Atual</td>";
$html .= "</tr>";

foreach($leitura as $mostra):
	$html .= "<tr>";

		$html .= "<td>".$mostra['id']."</td>";
		$html .= "<td>".$mostra['nome']."</td>";
		$endereco=substr($mostra['endereco'],0,50).','.$mostra['numero'].' - '.$mostra['complemento'];
			$html .= "<td>".$endereco."</td>";	

		$html .= "<td>". converteValor($mostra['orc_valor'])."</td>";
		 
		$html .= "<td>". converteData($mostra['orc_solicitacao'])."</td>";
		$html .= "<td>". converteData($mostra['orc_data']).'/'.$mostra['orc_hora'] ."</td>";

		$indicacaoId=$mostra['indicacao'];
		$indicacao = mostra('contrato_indicacao',"WHERE id ='$indicacaoId'");
		$html .= "<td>".$indicacao['nome']."</td>";

		$html .= "<td>". substr($mostra['orc_residuo'],0,15) ."</td>";
			
		$consultorId=$mostra['consultor'];
		$consultor = mostra('contrato_consultor',"WHERE id ='$consultorId'");
		$html .= "<td>".$consultor['nome']."</td>";

		$statusId = $mostra['status'];
		$status = mostra('contrato_status',"WHERE id ='$statusId'");
		$html .= "<td>".$status['nome']."</td>";

		$empresa_atualId = $mostra['empresa_atual'];
		$empresa = mostra('cadastro_visita_empresa_atual',"WHERE id ='$empresa_atualId'");
		$html .= "<td>".$empresa['nome']."</td>";
		

	$html .= "</tr>";
endforeach;

$html .= "<tr>";
	$html .= "<td></td>";
	$html .= "<td>Total de registros :". $total."</td>";
$html .= "</tr>";


echo $html;

?>