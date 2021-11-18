<?php

ob_start();
session_start();

require_once('../../../config/crud.php');
require_once('../../../config/funcoes.php');

$data1 = $_SESSION['inicio'];
$data2 = $_SESSION['fim'];
$rotaId =$_SESSION[ 'rotaColeta' ];

$total = conta('contrato_ordem',"WHERE data>='$data1' AND data<='$data2' AND status='13'");
$leitura = read('contrato_ordem',"WHERE data>='$data1' AND data<='$data2'  
														AND status='13' ORDER BY data ASC, rota ASC, hora ASC");
if(!empty($rotaId)){
	$rotaColeta = mostra('contrato_rota',"WHERE id ='$rotaId'");
	
	$total = conta('contrato_ordem',"WHERE data>='$data1' AND data<='$data2' AND rota='$rotaId' 
														AND status='13'");
	$leitura = read('contrato_ordem',"WHERE data>='$data1' AND data<='$data2' AND rota='$rotaId' 
														AND status='13' ORDER BY data ASC, rota ASC, hora ASC");
}
 
$nome_arquivo = "relatorio-ordem-rentabilidade-excel";
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
	$html .= "<td>Endereço</td>";

	$html .= "<td>Bairro</td>";
	$html .= "<td>Numero</td>";
	$html .= "<td>Tipo de Cobrança</td>";
	$html .= "<td>Coleta</td>";
	$html .= "<td>Previsto</td>";
	$html .= "<td>Coletado</td>";
	$html .= "<td>Vl Unit</td>";
	$html .= "<td>Faturado</td>";
	$html .= "<td>Vl Manifesto</td>";
	$html .= "<td>Data</td>";
	$html .= "<td>Rota</td>";
	$html .= "<td>Tio de Contrato</td>";

$html .= "</tr>";

foreach($leitura as $mostra):

	$html .= "<tr>";

		$contratoId = $mostra['id_contrato'];
		$contrato = mostra('contrato',"WHERE id ='$contratoId'");
		$controle = substr($contrato['controle'],0,6);

		$html .= "<td>".$mostra['id']."</td>";
		$html .= "<td>".$controle."</td>";

		$clienteId = $mostra['id_cliente'];
		$cliente = mostra('cliente',"WHERE id ='$clienteId '");
		$html .= "<td>".$cliente['nome']."</td>";

		$endereco=substr($cliente['endereco'],0,50).','.$cliente['numero'].' - '.$cliente['complemento'];
		$html .= "<td>".$endereco."</td>";	
		$html .= "<td>".$cliente['bairro']."</td>";

		$html .= "<td>".$mostra['id']."</td>";

		//Apenas Coletado e Minimo Mensal
		if ( $contrato[ 'cobranca_coleta' ] == '1' ) {
			$html .= "<td>Apenas Coletado e Minimo Mensal</td>";
		}

		//Minimo Diário e Minimo Mensal
		if ( $contrato[ 'cobranca_coleta' ] == '2' ) {
			$html .= "<td>Minimo Diario e Minimo Mensal</td>";
		}

		//Apenas Valor Mensal
		if ( $contrato[ 'cobranca_coleta' ] == '3' ) {
			$html .= "<td>Apenas Valor Mensal</td>";
		}

		//Apenas Coletado
		if ( $contrato[ 'cobranca_coleta' ] == '4' ) {
			$html .= "<td>Apenas Coletado</td>";

		}

		//Quantidade Mínima Mensal
		if ( $contrato[ 'cobranca_coleta' ] == '5' ) {
			$html .= "<td>Quantidade Minima Mensal</td>";
		}


		$tipoColetaId = $mostra['tipo_coleta1'];
    	$coleta = mostra('contrato_tipo_coleta',"WHERE id ='$tipoColetaId'");
		$html .= "<td>".$coleta['nome']."</td>";
		$dataroteiro=$mostra['data'];

	//$contratoColeta = mostra( 'contrato_coleta', "WHERE id AND inicio<='$dataroteiro' AND vencimento>='$dataroteiro' AND id_contrato='$contratoId'  AND tipo_coleta='$tipoColetaId'" );

	$contratoColeta = mostra( 'contrato_coleta', "WHERE id AND inicio<='$dataroteiro' AND vencimento>='$dataroteiro' AND id_contrato='$contratoId'" );


		$html .= "<td>".$contratoColeta['quantidade']."</td>";

		//Apenas Valor Mensal
		if ( $contrato[ 'cobranca_coleta' ] == '3' ) {
			$html .= "<td> ".$contratoColeta['quantidade']."</td>";
		}else{
			
			$html .= "<td>".$mostra['quantidade1']."</td>";

		}
		
		$html .= "<td>".converteValor($contratoColeta['valor_unitario'])."</td>";

		$valor=$contratoId = $mostra['quantidade1']*$contratoColeta['valor_unitario'];

		if($mostra['quantidade1']==0){
			$valor = $contratoColeta['quantidade'] * $contratoColeta['valor_unitario'];
		}
		if ( $contrato[ 'cobranca_coleta' ] == '3' ) {
			$valor = $contratoColeta['quantidade'] * $contratoColeta['valor_unitario'];
		}

		//Minimo Diário e Minimo Mensal
		if ( $contrato[ 'cobranca_coleta' ] == '2' ) {
			if($mostra['quantidade1']<$contratoColeta['quantidade'] ){
				$valor = $contratoColeta['quantidade'] * $contratoColeta['valor_unitario'];
			}
		}
 
		$html .= "<td>". converteValor($valor)."</td>";

		$html .= "<td>". converteValor($contrato['manifesto_valor'])."</td>";
 
		$rotaId =$mostra[ 'rota' ];
		$rotaColeta = mostra('contrato_rota',"WHERE id ='$rotaId'");

		$html .= "<td>".converteData($mostra['data'])."</td>";

		$html .= "<td>".$rotaColeta['nome']."</td>";

		$contratoTipoId = $contrato['contrato_tipo'];
		$contratoTipo = mostra('contrato_tipo',"WHERE id ='$contratoTipoId'");

		$html .= "<td>".$contratoTipo['nome']."</td>";

		$valorTotal=$valorTotal+$valor;

	$html .= "</tr>";

endforeach;

echo $html;

?>