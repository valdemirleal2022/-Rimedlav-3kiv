<?php

ob_start();
session_start();

require_once('../../../config/crud.php');
require_once('../../../config/funcoes.php');

$data1 = $_SESSION['inicio'];
$data2 = $_SESSION['fim'];
 
$leitura = read('contrato',"WHERE id AND tipo='2' AND data_suspensao>='$data1' 
											  AND data_suspensao<='$data2' AND status='19' 
											  ORDER BY data_suspensao ASC");

$nome_arquivo = "contrato-suspenso-temporario";
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
	$html .= "<td>Bairro</td>";
	$html .= "<td>Endereco</td>";
	$html .= "<td>Consultor</td>";
	$html .= "<td>Valor Mensal</td>";
	$html .= "<td>A partir</td>";
	$html .= "<td>Motivo</td>";
	$html .= "<td>Tipo de Contrato</td>";
	$html .= "<td>Rota</td>";
	$html .= "<td>Tipo de Coleta</td>";


	$html .= "<td>Ultimo Fat</td>";

	$html .= "<td>Data Ultimo Faturamento</td>";
	$html .= "<td>Valor Ultimo Faturamento</td>";

	$html .= "<td>Faturamento de Janeiro</td>";


$html .= "</tr>";

foreach($leitura as $mostra):

	$html .= "<tr>";
		$html .= "<td>".$mostra['id']."</td>";

		$contratoId = $mostra['id'];

		$contrato = mostra('contrato',"WHERE id = '$contratoId'");

		$clienteId = $mostra['id_cliente'];
		$dataSuspensao = $mostra['data_suspensao'];

		$cliente = mostra('cliente',"WHERE id ='$clienteId '");
		$html .= "<td>".$cliente['nome']."</td>";
		$endereco=substr($cliente['endereco'],0,50).','.$cliente['numero'].' - '.$cliente['complemento'];
		$html .= "<td>".$endereco."</td>";
		$html .= "<td>".$cliente['bairro']."</td>";

		$consultorId=$mostra['consultor'];
		$consultor = mostra('contrato_consultor',"WHERE id ='$consultorId'");
		$html .= "<td>".$consultor['nome']."</td>";

		$contratoColeta = mostra('contrato_coleta',"WHERE id AND id_contrato='$contratoId' ORDER BY vencimento ASC");

		$html .= "<td>".converteValor($contratoColeta['valor_mensal'])."</td>";

		$contratoBaixa = mostra('contrato_baixa',"WHERE id_contrato ='$contratoId' AND data ='$dataSuspensao' AND tipo='2'");
		$html .= "<td>".converteData($dataSuspensao )."</td>";
		$html .= "<td>". substr($contratoBaixa['motivo'],0,20) ."</td>";

			
		$statusId=$mostra['status'];
		$status = mostra('contrato_status',"WHERE id ='$statusId'");

		$tipoContratoId=$mostra['contrato_tipo'];
		$tipoContrato= mostra('contrato_tipo',"WHERE id ='$tipoContratoId'");
		$html .= "<td>".$tipoContrato['nome']."</td>"; 

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
	
		$contratoColeta = mostra('contrato_coleta',"WHERE id_contrato ='$contratoId'");
		$tipoColetaId = $contratoColeta['tipo_coleta'];
        $coleta = mostra('contrato_tipo_coleta',"WHERE id ='$tipoColetaId'");
		$html .= "<td>".$coleta['nome']."</td>";

		$dataSuspensao = $mostra['data_suspensao'];

		$receber = mostra('receber',"WHERE id_contrato ='$contratoId' AND emissao<='$dataSuspensao' ORDER BY vencimento ASC");
		$html .= "<td>". converteValor($receber['valor'])."</td>";	 

		$receber2 = mostra('receber',"WHERE id_contrato ='$contratoId' AND emissao>'$dataSuspensao' ORDER BY vencimento ASC");
	 
		$html .= "<td>". converteData($receber2['emissao'])."</td>";
		$html .= "<td>". converteValor($receber2['valor'])."</td>";

		$mes = '1/'+date('Y');
		$mesano = explode('/',$mes);

		$faturamentoJaneiro = mostra('receber',"WHERE id_contrato ='$contratoId' AND month(emissao)='01' AND Year(emissao)='2020'");
		$html .= "<td>". converteValor($faturamentoJaneiro['valor'])."</td>";

	$html .= "</tr>";
endforeach;

echo $html;

?>