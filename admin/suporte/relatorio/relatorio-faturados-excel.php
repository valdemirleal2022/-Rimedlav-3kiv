<?php

ob_start();
session_start();

require_once('../../../config/crud.php');
require_once('../../../config/funcoes.php');

$data1 = $_SESSION['inicio'];
$data2 = $_SESSION['fim'];
$contratoTipo=$_SESSION['contratoTipo'];
$enviar_boleto_correio=$_SESSION['enviar_boleto_correio'];

$valor_total = soma('receber',"WHERE emissao>='$data1' AND emissao<='$data2'",'valor');
$total = conta('receber',"WHERE emissao>='$data1' AND emissao<='$data2'");
$leitura = read('receber',"WHERE emissao>='$data1' AND emissao<='$data2' ORDER BY emissao ASC");

if ($enviar_boleto_correio=='1') {
	$valor_total = soma('receber',"WHERE emissao>='$data1' AND emissao<='$data2' AND enviar_boleto_correio='1'",'valor');
	$total = conta('receber',"WHERE emissao>='$data1' AND emissao<='$data2' AND enviar_boleto_correio='1'");
	$leitura = read('receber',"WHERE emissao>='$data1' AND emissao<='$data2' AND enviar_boleto_correio='1' ORDER BY emissao ASC");
}

if (!empty($contratoTipo)) {
	$valor_total = soma('receber',"WHERE emissao>='$data1' AND emissao<='$data2' AND contrato_tipo='$contratoTipo' ",'valor');
	$total = conta('receber',"WHERE emissao>='$data1' AND emissao<='$data2' AND contrato_tipo='$contratoTipo' ");
	$leitura = read('receber',"WHERE emissao>='$data1' AND emissao<='$data2' AND contrato_tipo='$contratoTipo'  ORDER BY emissao ASC");
}

$nome_arquivo = "relatorio-faturados";
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
	$html .= "<td>Endereço</td>";
	$html .= "<td>Bairro</td>";
	$html .= "<td>Cidade</td>";
	$html .= "<td>Contrato</td>";
	$html .= "<td>Tipo de Contrato</td>";
	$html .= "<td>Inicio</td>";

	$html .= "<td>Consultor</td>";
 
	$html .= "<td>Valor</td>";
	$html .= "<td>Desconto</td>";
	$html .= "<td>Taxa</td>";

	
	$html .= "<td>Emisssao</td>";
	$html .= "<td>Vencimento</td>";
	$html .= "<td>Pagamento</td>";

	$html .= "<td>Nota</td>";
	$html .= "<td>Remessa</td>";

	$html .= "<td>Valor Unitario</td>";
	$html .= "<td>Manifesto</td>";
	$html .= "<td>Valor Unitário</td>";
	$html .= "<td>Manifesto Valor</td>";
	$html .= "<td>Tipo de Coleta</td>";
	$html .= "<td>Locacao</td>";

	$html .= "<td>Data do Faturamento</td>";

	$html .= "<td>Status</td>";
	$html .= "<td>Data da Ação</td>";

	$html .= "<td>Nota no Faturamento</td>";
	$html .= "<td>Dia do Vencimento</td>";

	$html .= "<td>Classificação</td>";
	
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

	
		$contratoId = $mostra['id_contrato'];
		$contrato = mostra('contrato',"WHERE id ='$contratoId'");

		$html .= "<td>".$contrato['id'].'|'.substr($contrato['controle'],0,6)."</td>";

		$tipoId = $contrato['contrato_tipo'];
		$tipo = mostra('contrato_tipo',"WHERE id ='$tipoId'");
		$html .= "<td>". $tipo['nome']."</td>";
		$html .= "<td>".converteData($contrato['inicio'])."</td>";

		$consultorId = $contrato['consultor'];
		$consultor = mostra('contrato_consultor',"WHERE id ='$consultorId'");
		$html .= "<td>".$consultor['nome']."</td>";

		$html .= "<td>".converteValor($mostra['valor'])."</td>";

		$html .= "<td>".converteValor($mostra['desconto'])."</td>";
		$html .= "<td>".converteValor($mostra['juros'])."</td>";
		
		$html .= "<td>".converteData($mostra['emissao'])."</td>";
		$html .= "<td>".converteData($mostra['vencimento'])."</td>";
		$html .= "<td>".converteData($mostra['pagamento'])."</td>";
		
		$html .= "<td>".$mostra['nota']."</td>";
		$html .= "<td>".$mostra['remessa']."</td>";

		$dataExtrato=$mostra['emissao'];

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

	$html .= "<td>". converteValor($contratoColeta['valor_unitario'])."</td>";

	$manifestoId = $contrato['manifesto'];
	$manifesto = mostra('contrato_manifesto',"WHERE id ='$manifestoId'");
	$html .= "<td>" . $manifesto['nome']."</td>";
	
	$html .= "<td>". converteValor($contrato['manifesto_valor'])."</td>";
	$html .= "<td>". converteValor($valorManifesto)."</td>";


	$html .= "<td>". $tipoColeta['nome']."</td>";
	$html .= "<td>". $valorLocacao."</td>";


	$html .= "<td>". date('d/m/Y H:i',strtotime($mostra['data_faturamento']))."</td>";
	

	$status='';
	$dataAcao='';
	
		if($contrato['status']==5){
			
			$status='Ativo';
		 	 					 
		}elseif($contrato['status']==6){
			
			$status='Suspenso';
		 	$dataAcao=converteData($contrato['data_suspensao']);
				 
		}elseif($contrato['status']==9){
			
			$status='Cancelado';
			$dataAcao=converteData($contrato['data_cancelamento']);
						
		}elseif($contrato['status']==10){
			$status='Juridico';
			$dataAcao=converteData($contrato['data_judicial']);
			
		}elseif($contrato['status']==19){
			$status='Suspenso Temporariamente';
			$dataAcao=converteData($contrato['data_suspensao']);
			
		}else{
			
		}
			
		$html .= "<td>". $status."</td>";
		$html .= "<td>". $dataAcao."</td>";

		$dataNovos = date("Y-m-d", strtotime("-30 day"));

		//if($contrato['inicio']>=$dataNovos){
//			$html .= "<td>* Novo</td>";
//		}else{
//			$html .= "<td>-</td>";
//		}

		if($contrato['nota_no_faturamento']==1){
			$html .= "<td>Sim</td>";
		}else{
			$html .= "<td>Não</td>";
		}
		
		$html .= "<td>". $contrato['dia_vencimento'] ."</td>";


		$classificacaoId = $cliente['classificacao'];
		$classificacao = mostra('cliente_classificacao',"WHERE id ='$classificacaoId'");
		$html .= "<td>" . $classificacao['nome']."</td>";
	
	 
	$html .= "</tr>";

endforeach;

echo $html;

?>