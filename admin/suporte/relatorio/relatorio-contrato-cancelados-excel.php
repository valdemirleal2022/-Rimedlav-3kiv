<?php

ob_start();
session_start();

require_once('../../../config/crud.php');
require_once('../../../config/funcoes.php');


$data1 = $_SESSION['inicio'];
$data2 = $_SESSION['fim'];

$valor_total = soma('contrato',"WHERE id AND status='9' AND data_cancelamento>='$data1' 
											  AND data_cancelamento<='$data2'",'valor_mensal');
$total = conta('contrato',"WHERE id AND data_cancelamento>='$data1' 
												AND data_cancelamento<='$data2' AND status='9'");
$leitura = read('contrato',"WHERE id AND data_cancelamento>='$data1' 
											  AND data_cancelamento<='$data2' AND status='9' 
											  ORDER BY data_cancelamento ASC");

$nome_arquivo = "contratos-cancelados";
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
	$html .= "<td>Cidade</td>";
	$html .= "<td>Consultor</td>";
	$html .= "<td>Inicio</td>";
	$html .= "<td>Valor Mensal</td>";
	$html .= "<td>Data Solicitacao</td>";
	$html .= "<td>A partir</td>";
	$html .= "<td>Rota</td>";
	$html .= "<td>Motivo</td>";
	$html .= "<td>Tipo de Contrato</td>";
	$html .= "<td>Motivo Cancelamento</td>";
	$html .= "<td>Coleta</td>";

	$html .= "<td>Data Ultimo Faturamento</td>";
	$html .= "<td>Valor Ultimo Faturamento</td>";

$html .= "</tr>";

foreach($leitura as $mostra):

	$html .= "<tr>";

		$html .= "<td>".$mostra['id'].'|'.substr($mostra['controle'],0,6)."</td>";

		$contratoId = $mostra['id'];
		$clienteId = $mostra['id_cliente'];

		$cliente = mostra('cliente',"WHERE id ='$clienteId '");
		$html .= "<td>".$cliente['nome']."</td>";
		$endereco=substr($cliente['endereco'],0,50).','.$cliente['numero'].' - '.$cliente['complemento'];
		$html .= "<td>".$endereco."</td>";
		$html .= "<td>".$cliente['bairro']."</td>";

		$html .= "<td>".$cliente['cidade']."</td>";

		$consultorId=$mostra['consultor'];
		$consultor = mostra('contrato_consultor',"WHERE id ='$consultorId'");
		$html .= "<td>".$consultor['nome']."</td>";

		$html .= "<td>".converteData($mostra['inicio'])."</td>";

		$html .= "<td>".converteValor($mostra['valor_mensal'])."</td>";

		$contratoCancelado = mostra('contrato_cancelamento',"WHERE id AND id_contrato='$contratoId'  ORDER BY data_solicitacao DESC");

		$html .= "<td>".converteData($contratoCancelado['data_solicitacao'])."</td>";

		$html .= "<td>".converteData($mostra['data_cancelamento'])."</td>";

		if(!empty($mostra[ 'domingo_rota1' ])){
			$rotaId = $mostra[ 'domingo_rota1' ];
			$rota = mostra( 'contrato_rota', "WHERE id AND id='$rotaId'" );
			$rota= $rota[ 'nome' ];
		}
		if(!empty($mostra[ 'segunda_rota1' ])){
			$rotaId = $mostra[ 'segunda_rota1' ];
			$rota = mostra( 'contrato_rota', "WHERE id AND id='$rotaId'" );
			$rota= $rota[ 'nome' ];
		}
		if(!empty($mostra[ 'terca_rota1' ])){
			$rotaId = $mostra[ 'terca_rota1' ];
			$rota = mostra( 'contrato_rota', "WHERE id AND id='$rotaId'" );
			$rota= $rota[ 'nome' ];
		}
		if(!empty($mostra[ 'quarta_rota1' ])){
			$rotaId = $mostra[ 'quarta_rota1' ];
			$rota = mostra( 'contrato_rota', "WHERE id AND id='$rotaId'" );
			$rota= $rota[ 'nome' ];
		}
		if(!empty($mostra[ 'quinta_rota1' ])){
			$rotaId = $mostra[ 'quinta_rota1' ];
			$rota = mostra( 'contrato_rota', "WHERE id AND id='$rotaId'" );
			$rota= $rota[ 'nome' ];
		}
		if(!empty($mostra[ 'sexta_rota1' ])){
			$rotaId = $mostra[ 'sexta_rota1' ];
			$rota = mostra( 'contrato_rota', "WHERE id AND id='$rotaId'" );
			$rota= $rota[ 'nome' ];
		}
		if(!empty($mostra[ 'sabado_rota1' ])){
			$rotaId = $mostra[ 'sabado_rota1' ];
			$rota = mostra( 'contrato_rota', "WHERE id AND id='$rotaId'" );
			$rota= $rota[ 'nome' ];
		}

		$html .= "<td>".$rota."</td>";

		$contratoBaixa = mostra('contrato_baixa',"WHERE id_contrato ='$contratoId' AND tipo='5' ORDER BY interacao ASC");
		$html .= "<td>". substr($contratoBaixa['motivo'],0,60) ."</td>";
			
		$contratoTipoId = $mostra['contrato_tipo'];
		$monstraContratoTipo = mostra('contrato_tipo',"WHERE id ='$contratoTipoId'");

		$html .= "<td>".$monstraContratoTipo['nome'] ."</td>";

		$motivoCancelamentoId = $contratoBaixa['motivo_cancelamento'];
		$motivoCancelamento = mostra('contrato_cancelamento_motivo',"WHERE id ='$motivoCancelamentoId'");

		$html .= "<td>".$motivoCancelamento['nome'] ."</td>";


		$contratoColeta = mostra('contrato_coleta',"WHERE id_contrato ='$contratoId'");
		$tipoColetaId = $contratoColeta['tipo_coleta'];
    	$coleta = mostra('contrato_tipo_coleta',"WHERE id ='$tipoColetaId'");
		$html .= "<td>".$coleta['nome']."</td>";

		$receber = mostra('receber',"WHERE id_contrato ='$contratoId' ORDER BY vencimento ASC");

		$html .= "<td>". converteData($receber['emissao'])."</td>";
		$html .= "<td>". converteValor($receber['valor'])."</td>";

		
	$html .= "</tr>";
endforeach;

echo $html;

?>