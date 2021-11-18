<?php

ob_start();
session_start();


require_once('../../../config/crud.php');
require_once('../../../config/funcoes.php');


$data1 = $_SESSION['inicio'];
$data2 = $_SESSION['fim'];

$total = conta('estoque_equipamento_retirada',"WHERE id AND data_entrega>='$data1' 
					 AND data_entrega<='$data2'");
$leitura = read('estoque_equipamento_retirada',"WHERE id AND data_entrega>='$data1' 
					 AND data_entrega<='$data2' ORDER BY data_entrega ASC");

$nome_arquivo = "relatorio-equipamento-retirada";
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
	$html .= "<td>Equipamento</td>";
	$html .= "<td>Bairro</td>";
	$html .= "<td>Endereco</td>";
	$html .= "<td>Cliente</td>";
	$html .= "<td>Tipo</td>";
	$html .= "<td>Quant</td>";
	$html .= "<td>Solicitacao</td>";
	$html .= "<td>Entrega</td>";
	$html .= "<td>Status</td>";
	$html .= "<td>Observacao</td>";
	$html .= "<td>Consultor</td>";
	$html .= "<td>Rota</td>";
$html .= "</tr>";

foreach($leitura as $mostra):
	$html .= "<tr>";

		$equipamentoId = $mostra['id_equipamento'];
		$contratoId = $mostra['id_contrato'];
		$clienteId = $mostra['id_cliente'];

		$clienteId = $mostra['id_cliente'];
		$cliente = mostra('cliente',"WHERE id ='$clienteId '");

		$equipamento = mostra('estoque_equipamento',"WHERE id ='$equipamentoId'");

		$html .= "<td>".$mostra['id']."</td>";
		$html .= "<td>".$equipamento['nome']."</td>";

		$endereco=substr($cliente['endereco'],0,50).','.$cliente['numero'].' - '.$cliente['complemento'];

		$html .= "<td>".$cliente['bairro']."</td>";
		$html .= "<td>".$endereco."</td>";
		$html .= "<td>".$cliente['nome']."</td>";

		if($mostra['tipo'] == '1'){
			$html .= "<td>Troca</td>";
		}elseif($mostra['tipo'] == '2'){
			$html .= "<td>Entrega</td>";
		}elseif($mostra['tipo'] == '3'){
			$html .= "<td>Retirada</td>";
		}else{
			$html .= "<td>-</td>";
		} 

		$html .= "<td>".$mostra['quantidade']."</td>";
		$html .= "<td>".converteData($mostra['data_solicitacao'])."</td>";
		$html .= "<td>".converteData($mostra['data_entrega'])."</td>";

		$html .= "<td>". $mostra['status'] ."</td>";
		$html .= "<td>". $mostra['observacao'] ."</td>";
		
		$contrato = mostra('contrato',"WHERE id ='$contratoId '");
		$consultorId=$contrato['consultor'];
		$consultor = mostra('contrato_consultor',"WHERE id ='$consultorId'");
		$html .= "<td>".$consultor['nome']."</td>";

		
	if(!empty($contrato[ 'domingo_rota1' ])){
		$rotaId = $contrato[ 'domingo_rota1' ];
		$rota = mostra( 'contrato_rota', "WHERE id AND id='$rotaId'" );
		$rota= $rota[ 'nome' ];
	}
	if(!empty($contrato[ 'segunda_rota1' ])){
		$rotaId = $contrato[ 'segunda_rota1' ];
		$rota = mostra( 'contrato_rota', "WHERE id AND id='$rotaId'" );
		$rota= $rota[ 'nome' ];
	}
	if(!empty($contrato[ 'terca_rota1' ])){
		$rotaId = $contrato[ 'terca_rota1' ];
		$rota = mostra( 'contrato_rota', "WHERE id AND id='$rotaId'" );
		$rota= $rota[ 'nome' ];
	}
	if(!empty($contrato[ 'quarta_rota1' ])){
		$rotaId = $contrato[ 'quarta_rota1' ];
		$rota = mostra( 'contrato_rota', "WHERE id AND id='$rotaId'" );
		$rota= $rota[ 'nome' ];
	}
	if(!empty($contrato[ 'quinta_rota1' ])){
		$rotaId = $contrato[ 'quinta_rota1' ];
		$rota = mostra( 'contrato_rota', "WHERE id AND id='$rotaId'" );
		$rota= $rota[ 'nome' ];
	}
	if(!empty($contrato[ 'sexta_rota1' ])){
		$rotaId = $contrato[ 'sexta_rota1' ];
		$rota = mostra( 'contrato_rota', "WHERE id AND id='$rotaId'" );
		$rota= $rota[ 'nome' ];
	}
	if(!empty($contrato[ 'sabado_rota1' ])){
		$rotaId = $contrato[ 'sabado_rota1' ];
		$rota = mostra( 'contrato_rota', "WHERE id AND id='$rotaId'" );
		$rota= $rota[ 'nome' ];
	}
	
	$html .= "<td>".$rota."</td>"; 
			
		 
	$html .= "</tr>";
endforeach;

echo $html;

?>