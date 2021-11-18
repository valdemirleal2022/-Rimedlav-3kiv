<?php

ob_start();
session_start();

require_once('../../../config/crud.php');
require_once('../../../config/funcoes.php');

$data1 = $_SESSION['inicio'];
$data2 = $_SESSION['fim'];

if(isset($_SESSION['contratoTipo'])){
	$contratoTipo = $_SESSION['contratoTipo'];
}

if(isset($_SESSION[ 'contratoCobranca' ])){
	$contratoCobranca =$_SESSION[ 'contratoCobranca' ];
}

$valor_total = soma('contrato',"WHERE id AND tipo='2' AND inicio>='$data1' 
											AND inicio<='$data2'",'valor_mensal');
$total = conta('contrato',"WHERE id AND tipo='2' AND inicio>='$data1' AND inicio<='$data2'");

$leitura = read('contrato',"WHERE id AND tipo='2' AND inicio>='$data1'  
											AND inicio<='$data2' ORDER BY inicio ASC");


if(isset($contratoTipo)){
	
	$valor_total = soma('contrato',"WHERE id AND tipo='2' AND status='5' AND inicio>='$data1'  AND inicio<='$data2' AND contrato_tipo='$contratoTipo' AND status='5'",'valor_mensal');
			
	$total = conta('contrato',"WHERE id AND tipo='2' AND inicio>='$data1' AND inicio<='$data2' AND contrato_tipo='$contratoTipo' AND status='5'");
			
	$leitura = read('contrato',"WHERE id AND tipo='2' AND inicio>='$data1'  AND inicio<='$data2' AND contrato_tipo='$contratoTipo' AND status='5' ORDER BY inicio ASC");
	
}
		
if(isset($contratoCobranca)){
	
	$valor_total = soma('contrato',"WHERE id AND tipo='2' AND status='5' AND inicio>='$data1'  AND inicio<='$data2' AND cobranca_coleta='$contratoCobranca' AND status='5'",'valor_mensal');
			
	$total = conta('contrato',"WHERE id AND tipo='2' AND inicio>='$data1' AND inicio<='$data2' AND cobranca_coleta='$contratoCobranca' AND status='5'");
			
	$leitura = read('contrato',"WHERE id AND tipo='2' AND inicio>='$data1'  AND inicio<='$data2' AND cobranca_coleta='$contratoCobranca' AND status='5' ORDER BY inicio ASC");
	
}

$nome_arquivo = "contratos-ativos";
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
	$html .= "<td>Estado</td>";
	$html .= "<td>CNPJ</td>";
	$html .= "<td>Consultor</td>";

	$html .= "<td>Tipo de Coleta</td>";
	$html .= "<td>Quantidade</td>";
	$html .= "<td>Valor Unitario</td>";
	$html .= "<td>Valor Mensal</td>";

	$html .= "<td>Aprovacao</td>";
	$html .= "<td>Inicio</td>";
	$html .= "<td>Fechamento</td>";
	$html .= "<td>Tipo</td>";
	$html .= "<td>Cobranca</td>";
	$html .= "<td>status</td>";
	$html .= "<td>Quant Minima</td>";
	$html .= "<td>Email</td>";
	$html .= "<td>Restrição</td>";

	$html .= "<td>Classificacao</td>";

	$html .= "<td>Data Ultimo Faturamento</td>";
	$html .= "<td>Valor Ultimo Faturamento</td>";

	$html .= "<td>Manifesto Valor</td>";
	$html .= "<td>Locacao</td>";

	$html .= "<td>Frequencia</td>";
	$html .= "<td>Dia da Semana</td>";


$html .= "</tr>";

foreach($leitura as $mostra):
	$html .= "<tr>";

		$html .= "<td>".$mostra['id']."</td>";
		$clienteId = $mostra['id_cliente'];
		$cliente = mostra('cliente',"WHERE id ='$clienteId '");
		$html .= "<td>".$cliente['nome']."</td>";

		$endereco=substr($cliente['endereco'],0,50).','.$cliente['numero'].' - '.$cliente['complemento'];
		$html .= "<td>".$endereco."</td>";

		$html .= "<td>".$cliente['bairro']."</td>";
		$html .= "<td>".$cliente['cidade']."</td>";
		$html .= "<td>".$cliente['uf']."</td>";

		$html .= "<td>".$cliente['cnpj'].$cliente['cpf']."</td>";

		$consultorId=$mostra['consultor'];
		$consultor = mostra('contrato_consultor',"WHERE id ='$consultorId'");
		$html .= "<td>".$consultor['nome']."</td>";

		$contratoId = $mostra['id'];
		$contratoColeta = mostra('contrato_coleta',"WHERE id_contrato ='$contratoId'");
		$tipoColetaId = $contratoColeta['tipo_coleta'];
    	$coleta = mostra('contrato_tipo_coleta',"WHERE id ='$tipoColetaId'");
		$html .= "<td>".$coleta['nome']."</td>";
		$html .= "<td>".$contratoColeta['quantidade']."</td>";
		$html .= "<td>".converteValor($contratoColeta['valor_unitario'])."</td>";

		$html .= "<td>".converteValor($mostra['valor_mensal'])."</td>";

		$html .= "<td>".converteData($mostra['aprovacao'])."</td>";
		$html .= "<td>".converteData($mostra['inicio'])."</td>";

		$html .= "<td>".$mostra['dia_fechamento']."</td>";
			
		$tipoContratoId=$mostra['contrato_tipo'];
		$tipoContrato= mostra('contrato_tipo',"WHERE id ='$tipoContratoId'");
		$html .= "<td>".$tipoContrato['nome']."</td>";

		$cobrancaId=$mostra['cobranca_coleta'];
		$cobranca = mostra('contrato_cobranca',"WHERE id ='$cobrancaId'");
		$html .= "<td>".$cobranca['nome']."</td>";

		$statusId=$mostra['status'];
		$status = mostra('contrato_status',"WHERE id ='$statusId'");
		$html .= "<td>".$status['nome']."</td>";

		$contratoId = $mostra['id'];
    	$contratoColeta = mostra('contrato_coleta',"WHERE id_contrato ='$contratoId'");

		$html .= "<td>".$contratoColeta['quantidade']."</td>";

		$html .= "<td>".$cliente['email']."</td>";

		if($cliente['nao_enviar_email']=='1'){
			$html .= "<td>Nao Enviar Email</td>";
		}elseif($cliente['nao_enviar_email']=='0'){
			$html .= "<td>Sem Restricaoo</td>";
		}else{
			$html .= "<td>-</td>";
		}
 
		$classificacaoId=$cliente['classificacao'];
		$classificacao = mostra('cliente_classificacao',"WHERE id ='$classificacaoId'");
		$html .= "<td>".$classificacao['nome']."</td>";

		$receber = mostra('receber',"WHERE id_contrato ='$contratoId' ORDER BY vencimento ASC");

		$html .= "<td>". converteData($receber['emissao'])."</td>";

		$html .= "<td>". converteValor($receber['valor'])."</td>";

		$dataExtrato=$receber['emissao'];
		$contrato = mostra('contrato',"WHERE id ='$contratoId'");

		// ALTERAÇÃO 28/02/2018
	
		// GERANDO DATAS DO EXTRATO 
		$fechamento = $contrato['dia_fechamento'];
		$vencimento = $contrato['dia_vencimento'];

		// FEVEREIRO AJUSTA DIA 29 E 30 PARA DIA 28
		$mesFaturamento = numeroMes($dataExtrato);
		$dia=$fechamento;
		if($mesFaturamento=='02' AND $dia>'28'){
			$fechamento='28';
		}

		// AJUSTADA DATA DE FATURAMENTO E VENCIMENTO
		$mes = date( 'm', strtotime( $dataExtrato ) );
		$ano = date( 'Y', strtotime( $dataExtrato ) );
		$faturamento =  date( "Y-m-d",mktime(0,0,0,$mes,$fechamento,$ano));
		$vencimento = date( "Y-m-d",mktime(0,0,0,$mes,$vencimento,$ano));

		// VENCIMENTO PROXIMO MES
		if($vencimento<$faturamento){
			$vencimento = date("Y-m-d", strtotime("$vencimento +1 month"));
		}

		$data1 = date( "Y-m-d", strtotime( "$faturamento -1 month" ) );
		$data2 = date( "Y-m-d", strtotime( "$faturamento -1 days" ) );

		// FEVEREIRO AJUSTA DIA 29
		if($mesFaturamento=='02' AND $dia=='29'){
			$data1 = date( "Y-m-d", strtotime( "$faturamento -1 month" ) );
			$data1 = date( "Y-m-d", strtotime( "$data1 +1 days" ) );
			$data2 = date( "Y-m-d", strtotime( "$faturamento" ) );
		}

		// FEVEREIRO AJUSTA DIA 30
		if($mesFaturamento=='02' AND $dia=='30'){
			$data1 = date( "Y-m-d", strtotime( "$faturamento -1 month" ) );
			$data1 = date( "Y-m-d", strtotime( "$data1 +2 days" ) );
			
			// ANO BISEXTO
			$data2 = date( "Y-m-d", strtotime( "$faturamento +1 days" ) );
		}
	
		// MARÇO AJUSTA DIA 30
		if($mesFaturamento=='03' AND $dia=='30'){
			$data1 = date( "Y-m-d", strtotime( "$faturamento -1 month" ) );
			$data1 = date( "Y-m-d", strtotime( "$data1 -1 days" ) );
			$data2 = date( "Y-m-d", strtotime( "$faturamento" ) );
		}

	$ordemTotal = 0 ;
	// ORDEM DE SERVIÇO GERADAS
	$readOrdem = read('contrato_ordem',"WHERE id AND id_contrato='$contratoId'  
					AND data>='$data1' AND data<='$data2' ORDER BY data ASC");
	if ($readOrdem) {
		foreach($readOrdem as $ordem):
			// SE REALIZADA A ORDEM DE SERVIÇO
			if($ordem['status']=='13'){ 
				
				$ordemTotal = $ordemTotal+1;

				$data = $ordem['data'];
				$coletaDiaria =  $ordem['quantidade1'];

				// NÃO COLETADO
					if($ordem['nao_coletada']=='1'){
						$ordemTotal = $ordemTotal-1;
					}else{
						// ORDEM ZERADA - cobrar mesmo zerado - 07/10/2018
						if($coletaDiaria=='0'){
							//$ordemTotal = $ordemTotal-1;
						}
					}
					
			}// FIM ORDEM REALIZADA
		endforeach;
	}

	// COBRA MANIFESTO
	$valorManifesto=0;
	if($contrato['manifesto']=='1'){
		$valorManifesto = $contrato['manifesto_valor']*$ordemTotal;
	}

	// COBRA MANIFESTO MESMO QUE DESMARCADO
	if($contrato['manifesto_valor']>'0'){
		$valorManifesto = $contrato['manifesto_valor']*$ordemTotal;
	}


	//  ABRE APENAS PARA VISUALIZAR NO EXTRATO E PEGAR O MINIMO MENSAL
	$readContratoColeta = read('contrato_coleta',"WHERE id AND inicio<='$faturamento' AND vencimento>'$faturamento' AND id_contrato='$contratoId' ORDER BY vencimento ASC");
	if ( $readContratoColeta ) {
		foreach($readContratoColeta as $contratoColeta):
				// PEGAR TIPO DE EQUIPAMENTO E MINIMO MENSAL
				$tipoEquipamento=$contratoColeta['tipo_coleta'];
				$tipoColeta = mostra('contrato_tipo_coleta',"WHERE id ='$tipoEquipamento'");
				$valorMinimoMensal = $contratoColeta['valor_mensal'];
				$valorEquipamentoLocacao = $tipoColeta['valor_locacao'];
		endforeach;
	}

	$readContratoColeta = read('contrato_coleta',"WHERE id AND inicio<='$data' AND vencimento>='$data' AND id_contrato='$contratoId'");
				if ($readContratoColeta ) {
					foreach($readContratoColeta as $contratoColeta):
							$quantidadeContrato=$contratoColeta['quantidade'];
					endforeach;
				}
	// LOCAÇÃO DE EQUIPAMENTOS
	$valorLocacao =0;
	if($contrato['cobrar_locacao']=='1'){
		$valorLocacao = $valorEquipamentoLocacao*$quantidadeContrato;
	}

	$manifestoId = $contrato['manifesto'];
	$manifesto = mostra('contrato_manifesto',"WHERE id ='$manifestoId'");
	$html .= "<td>". converteValor($valorManifesto)."</td>";
	$html .= "<td>". $valorLocacao."</td>";

	
$diaSemana='';
if($contrato['domingo']==1){
	$diaSemana = ' Dom';
}
if($contrato['segunda']==1){
	$diaSemana = $diaSemana . ' Seg';
}
if($contrato['terca']==1){
	$diaSemana = $diaSemana . ' Ter';
}
if($contrato['quarta']==1){
	$diaSemana = $diaSemana . ' Qua';
}
if($contrato['quinta']==1){
	$diaSemana = $diaSemana . ' Qui';
}
if($contrato['sexta']==1){
	$diaSemana = $diaSemana . ' Sex';
}
if($contrato['sabado']==1){
	$diaSemana = $diaSemana . ' Sabado';
}

// 1 - semanal - 2 quinzenal - 3 mensal - 4 avulso
$frequenciaId = $contrato[ 'frequencia' ];
$frequencia = mostra( 'contrato_frequencia', "WHERE id AND id='$frequenciaId'" );
$frequencia = $frequencia[ 'nome' ];

$html .= "<td>". $frequencia."</td>";
$html .= "<td>". $diaSemana."</td>";
 
	$html .= "</tr>";
endforeach;

echo $html;

?>