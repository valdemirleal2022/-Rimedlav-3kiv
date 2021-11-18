<?php

ob_start();
session_start();

require_once('../../../config/crud.php');
require_once('../../../config/funcoes.php');

$data1 = $_SESSION['inicio'];
$data2 = $_SESSION['fim'];
 
$nome_arquivo = "contrato-suspenso-reativos";
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

	$html .= "<td>Endereco</td>";
	$html .= "<td>Bairro</td>";

	$html .= "<td>Consultor</td>";
	$html .= "<td>Valor Mensal</td>";
	$html .= "<td>A partir</td>";
	$html .= "<td>Motivo</td>";
	$html .= "<td>Tipo de Contrato</td>";
	$html .= "<td>Rota</td>";

	$html .= "<td>Tipo de Coleta</td>";
	$html .= "<td>Quantidade</td>";
	$html .= "<td>Valor Unitário</td>";

	$html .= "<td>Ultimo Fat</td>";

	$html .= "<td>Data Ultimo Faturamento</td>";
	$html .= "<td>Valor Ultimo Faturamento</td>";


	$html .= "<td>Faturamento de Janeiro</td>";



$html .= "</tr>";

$leitura = read('contrato_baixa',"WHERE tipo='1' AND data>='$data1' AND data<='$data2'
											  ORDER BY data ASC");
foreach($leitura as $mostra):
		
		$contratoId = $mostra['id_contrato'];

		$leituraBaixa = read('contrato_baixa',"WHERE id_contrato ='$contratoId' AND tipo='8'");
		if($leituraBaixa ){
			
		  foreach($leituraBaixa as $contratoBaixa);
		  $html .= "<tr>";
			$contrato= mostra('contrato',"WHERE id='$contratoId'");
			$clienteId = $contrato['id_cliente'];
			$cliente= mostra('cliente',"WHERE id='$clienteId'");
			
			$html .= "<td>".$contrato['id']."</td>";
			
			$html .= "<td>".substr($contrato['controle'],0,6)."</td>";


			$html .= "<td>".$cliente['nome']."</td>";
			$endereco=substr($cliente['endereco'],0,50).','.$cliente['numero'].' - '.$cliente['complemento'];
			$html .= "<td>".$endereco."</td>";
			$html .= "<td>".$cliente['bairro']."</td>";

			$consultorId=$contrato['consultor'];
			$consultor = mostra('contrato_consultor',"WHERE id ='$consultorId'");
			$html .= "<td>".$consultor['nome']."</td>";

			$html .= "<td>".converteValor($contrato['valor_mensal'])."</td>";

			$html .= "<td>".converteData($mostra['data'])."</td>";
			$html .= "<td>". substr($contratoBaixa['motivo'],0,20) ."</td>";

			
			$statusId=$contrato['status'];
			$status = mostra('contrato_status',"WHERE id ='$statusId'");

			$tipoContratoId=$contrato['contrato_tipo'];
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
			$html .= "<td>".$contratoColeta['quantidade']."</td>";
			$html .= "<td>".converteValor($contratoColeta['valor_unitario'])."</td>";
			$dataSuspensao = $contratoBaixa['data_suspensao'];

			$receber = mostra('receber',"WHERE id_contrato ='$contratoId' AND emissao<='$dataSuspensao' ORDER BY vencimento ASC");
			$html .= "<td>". converteValor($receber['valor'])."</td>";	 

			$receber2 = mostra('receber',"WHERE id_contrato ='$contratoId' AND emissao>'$dataSuspensao' ORDER BY vencimento ASC");
	 
			$html .= "<td>". converteData($receber2['emissao'])."</td>";
			$html .= "<td>". converteValor($receber2['valor'])."</td>";
			
				$faturamentoJaneiro = mostra('receber',"WHERE id_contrato ='$contratoId' AND month(emissao)='01' AND Year(emissao)='2020'");
		$html .= "<td>". converteValor($faturamentoJaneiro['valor'])."</td>";
  

	$html .= "</tr>";
		
	} 
	
	
endforeach;

echo $html;

?>