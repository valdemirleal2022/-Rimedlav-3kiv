<?php

ob_start();
session_start();

require_once('../../../config/crud.php');
require_once('../../../config/funcoes.php');


$data1 = $_SESSION['inicio'];
$data2 = $_SESSION['fim'];

$total = conta('contrato_cancelamento',"WHERE id AND data_solicitacao>='$data1' 
												AND data_solicitacao<='$data2'");
$leitura = read('contrato_cancelamento',"WHERE id AND data_solicitacao>='$data1' 
					 AND data_solicitacao<='$data2' ORDER BY data_solicitacao ASC");

if($_SESSION['ordem']=='2') {
	$leitura = read('contrato_cancelamento',"WHERE id AND data_encerramento>='$data1' 
					 AND data_encerramento<='$data2' ORDER BY data_encerramento ASC");
	$total = conta('contrato_cancelamento',"WHERE id AND data_solicitacao>='$data1' 
												AND data_solicitacao<='$data2'");
}

$nome_arquivo = "solicitacao-cancelamento";
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
	$html .= "<td>Consultor</td>";
	$html .= "<td>Coleta</td>";
	$html .= "<td>Valor Mensal</td>";
	$html .= "<td>Solicitacao</td>";
	$html .= "<td>Encerramento</td>";
	$html .= "<td>Rota</td>";
	$html .= "<td>Status do Cancelamento</td>";
	$html .= "<td>Recuperada</td>";
	$html .= "<td>Motivo</td>";	
	$html .= "<td>Status do Contrato</td>";


	$html .= "<td>Data Ultimo Faturamento</td>";
	$html .= "<td>Valor Ultimo Faturamento</td>";
	
$html .= "</tr>";

foreach($leitura as $mostra):

	$html .= "<tr>";


		$contratoId = $mostra['id_contrato'];
		$clienteId = $mostra['id_cliente'];
		$contrato = mostra('contrato',"WHERE id ='$contratoId '");
		$cliente = mostra('cliente',"WHERE id ='$clienteId '");

		$html .= "<td>".$mostra['id'].'|'.substr($contrato['controle'],0,6)."</td>";


		$cliente = mostra('cliente',"WHERE id ='$clienteId '");
		$html .= "<td>".$cliente['nome']."</td>";
		$endereco=substr($cliente['endereco'],0,50).','.$cliente['numero'].' - '.$cliente['complemento'];
		$html .= "<td>".$endereco."</td>";

		$html .= "<td>".$cliente['bairro']."</td>";

		$consultorId=$contrato['consultor'];
		$consultor = mostra('contrato_consultor',"WHERE id ='$consultorId'");
		$html .= "<td>".$consultor['nome']."</td>";

		$contratoColeta = mostra('contrato_coleta',"WHERE id_contrato ='$contratoId'");
		$tipoColetaId = $contratoColeta['tipo_coleta'];
    	$coleta = mostra('contrato_tipo_coleta',"WHERE id ='$tipoColetaId'");
		$html .= "<td>".$coleta['nome']."</td>";

		$html .= "<td>".converteValor($contrato['valor_mensal'])."</td>";

		$html .= "<td>".converteData($mostra['data_solicitacao'])."</td>";
		$html .= "<td>".converteData($mostra['data_encerramento'])."</td>";

		if($contrato[ 'domingo' ]=='1'){
			$rotaId = $contrato[ 'domingo_rota1' ];
		}
		if($contrato[ 'segunda' ]=='1'){
			$rotaId = $contrato[ 'segunda_rota1' ];
		}
		if($contrato[ 'terca' ]=='1'){
			$rotaId = $contrato[ 'terca_rota1' ];
		}
		if($contrato[ 'quarta' ]=='1'){
			$rotaId = $contrato[ 'quarta_rota1' ];
		}
		if($contrato[ 'quinta' ]=='1'){
			$rotaId = $contrato[ 'quinta_rota1' ];
		}
		if($contrato[ 'sexta' ]=='1'){
			$rotaId = $contrato[ 'sexta_rota1' ];
		}
		if($contrato[ 'sabado' ]=='1'){
			$rotaId = $contrato[ 'sabado_rota1' ];
		}
		$rota = mostra( 'contrato_rota', "WHERE id AND id='$rotaId'" );
		$rota= $rota[ 'nome' ];

		$html .= "<td>".$rota."</td>";
		$html .= "<td>".$mostra['status']."</td>";

		if ($mostra['recuperada']==1){
			$html .= "<td>SIM</td>";
		}elseif ($mostra['recuperada']==2){
			$html .= "<td>NAO</td>";
		}else{
			$html .= "<td>-</td>";
		}

		$motivoId=$mostra['motivo'];
		$motivo = mostra('contrato_cancelamento_motivo',"WHERE id ='$motivoId'");
		$html .= "<td>".$motivo['nome']."</td>";

		if($contrato['status']==5){
					$html .= "<td>Ativo</td>";
				}elseif($contrato['status']==6){
					$html .= "<td>Suspenso</td>";
				}elseif($contrato['status']==9){
					$html .= "<td>Cancelado</td>";
				}else{
					$html .= "<td>!</td>";
				}

		$receber = mostra('receber',"WHERE id_contrato ='$contratoId' ORDER BY vencimento ASC");

		$html .= "<td>". converteData($receber['emissao'])."</td>";
		$html .= "<td>". converteValor($receber['valor'])."</td>";

	$html .= "</tr>";

endforeach;

echo $html;

?>