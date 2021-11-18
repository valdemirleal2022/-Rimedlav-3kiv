<head>
    <meta charset="iso-8859-1">
</head>

<?php


$_SESSION[ 'naoencontrado' ] = 'Tipo : ' . $contratoTipo . 'Dia : ' .  $dia . 'Status : ' .  $status . ' Banco : ' . $bancoExtrato . ' Data : ' . converteData($dataExtrato);

//echo '<script">alert("' . $_SESSION[ 'naoencontrado' ] . '")</script>';

if (!isset($dia)) {
	header( 'Location: painel.php?execute=suporte/error' );
}
if (!isset($bancoExtrato)) {
	header( 'Location: painel.php?execute=suporte/error' );
}

	// status
	$total = conta('contrato',"WHERE id AND dia_fechamento='$dia' AND status='$status' 
					AND inicio<'$dataExtrato'");
	$leitura = read('contrato',"WHERE id AND dia_fechamento='$dia' AND status='$status' 
					AND inicio<'$dataExtrato' ORDER BY controle ASC");
	
	// FEVEREIRO
	if($mesFaturamento=='02' AND $dia=='28'){
		$total = conta('contrato',"WHERE id AND dia_fechamento>='$dia' AND status='$status' 
						AND inicio<'$dataExtrato'");
		$leitura = read('contrato',"WHERE id AND dia_fechamento>='$dia' AND status='$status' 
						AND inicio<'$dataExtrato' ORDER BY controle ASC");
	}

	if(!empty($contratoTipo)){
		$total = conta('contrato',"WHERE id AND dia_fechamento='$dia' AND status='$status' 
					AND inicio<'$dataExtrato' AND contrato_tipo='$contratoTipo'");
		$leitura = read('contrato',"WHERE id AND dia_fechamento='$dia' AND status='$status' 
					AND inicio<'$dataExtrato' AND contrato_tipo='$contratoTipo'
					ORDER BY controle ASC");
		
		// FEVEREIRO
		if($mesFaturamento=='02' AND $dia=='28'){
			$total = conta('contrato',"WHERE id AND dia_fechamento>='$dia' AND status='$status' 
					AND inicio<'$dataExtrato' AND contrato_tipo='$contratoTipo'");
			$leitura = read('contrato',"WHERE id AND dia_fechamento>='$dia' AND status='$status' 
					AND inicio<'$dataExtrato' AND contrato_tipo='$contratoTipo'
					ORDER BY controle ASC");
		}
	}

	

// CONTRATOS
if ( $leitura ) {
	foreach ( $leitura as $contrato ):
	
		
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
			$data2 = date( "Y-m-d", strtotime( "$faturamento" ) );
		}
	
		
		// MARÇO AJUSTA DIA 30
		if($mesFaturamento=='03' AND $dia=='30'){
			$data1 = date( "Y-m-d", strtotime( "$faturamento -1 month" ) );
			$data1 = date( "Y-m-d", strtotime( "$data1 -1 days" ) );
			$data2 = date( "Y-m-d", strtotime( "$faturamento" ) );
		}

		// CADASTRO DE CLIENTE
		$clienteId = $contrato[ 'id_cliente' ];
	    $contratoTipo = $contrato['contrato_tipo'];
		$cliente = mostra( 'cliente', "WHERE id = '$clienteId'" );

		// FORMA DE COBRANÇA
		$cobrancaId = $contrato[ 'cobranca_coleta' ];
		$cobrancaColeta = mostra( 'contrato_cobranca', "WHERE id AND id='$cobrancaId'" );
	
		$contratoId = $contrato['id'];
	
		$valorEquipamentoLocacao =0;
		$valorMinimoMensal =0;

			//  ABRE APENAS PARA VISUALIZAR NO EXTRATO E PEGAR O MINIMO MENSAL
		$readContratoColeta = read('contrato_coleta',"WHERE id AND inicio<'$faturamento' AND vencimento>='$faturamento' AND id_contrato='$contratoId' ORDER BY vencimento ASC");
	
		if ( $readContratoColeta ) {
			foreach($readContratoColeta as $contratoColeta):
			
				// PEGAR TIPO DE EQUIPAMENTO E MINIMO MENSAL
				$tipoEquipamento=$contratoColeta['tipo_coleta'];
				$tipoColeta = mostra('contrato_tipo_coleta',"WHERE id ='$tipoEquipamento'");
	
				$valorMinimoMensal = $contratoColeta['valor_mensal'];
				$valorEquipamentoLocacao = $tipoColeta['valor_locacao'];
			
			endforeach;
		 }else{

		}
	
	// CONTRATO SUSPENSO TEMPORARIAMENTE
	$hoje=date( "Y-m-d");
	$dataInicio = $data1;
	$valorMinimoMensalSalvo = $valorMinimoMensal;

	$leituraBaixa = read('contrato_baixa',"WHERE id_contrato ='$contratoId' AND tipo='8'");
	if($leituraBaixa ){
		foreach($leituraBaixa as $contratoBaixa)
		$dataSuspensao = $contratoBaixa['emissao'];
			
		$receber = mostra('receber',"WHERE id_contrato ='$contratoId' AND emissao<='$hoje' AND emissao!='$dataExtrato' ORDER BY emissao ASC");
		$data1 = $receber['emissao'];
		$valorMinimoMensal = 0;
		
		$leituraBaixa = read('contrato_baixa',"WHERE id AND id_contrato='$contratoId' AND data>'$dataSuspensao' AND tipo='1'");
		if($leituraBaixa){
			$data1 = $dataInicio;
			$valorMinimoMensal = $valorMinimoMensalSalvo;
		 
		}
	 
	}

	
	// CONTRATO SUSPENSO SO CONSIDER QUANTIDADE COLETADA
	if($contrato['status']==6){
		$data1 = $dataInicio;
		$valorMinimoMensal =0;
	}
 
	// FUNCAO DO EXTRATO IGUAL A CONSTRUÇÃO DO BOLETO


	// INICIALIZA COM TOTAL ZERADOS
	$ordemTotal=0;
	$minimoTotal=0;
	$extraTotal=0;

	$valorMinimoTotal=0;
	$valorExtraTotal=0;
	$valorTotal=0;

	// ORDEM DE SERVIÇO GERADAS
	$readOrdem = read('contrato_ordem',"WHERE id AND id_contrato='$contratoId'  
					AND data>='$data1' AND data<='$data2' ORDER BY data ASC");
	if ($readOrdem) {
		foreach($readOrdem as $ordem):
			
			// STATUS DO ORDEM
			$ordemId = $ordem['id'];
			$statusColeta = $ordem['status'];
			$statusColeta = mostra('contrato_status',"WHERE id ='$statusColeta'");
			$statusNome=$statusColeta['nome'];
		
			// PEGAR TIPO DE COLETA 1
			$tipoColeta1=$ordem['tipo_coleta1'];
		
			$tipoColeta = mostra('contrato_tipo_coleta',"WHERE id ='$tipoColeta1'");
			$tipoColetaNome = $tipoColeta['nome'];
		
			// INICIALIZA ZERADO O VALOR UNITARIO E EXTRA DA COLETA
			$coletaDiaria = 0 ;
			$valorColetaUnitario =0;
			$quantidadeExtra=0;
			$valorColetaExtra = 0;
		
			// SE REALIZADA A ORDEM DE SERVIÇO
			if($ordem['status']=='13'){ 

				$ordemTotal = $ordemTotal+1;

				$data = $ordem['data'];
				$coletaDiaria =  $ordem['quantidade1'];

				// SE ENCONTROU EQUIPAMENTO PEGAR VALOR DA COLETA
				//$readContratoColeta = read('contrato_coleta',"WHERE id AND inicio<='$data' AND vencimento>='$data' AND id_contrato='$contratoId' AND tipo_coleta='$tipoColeta1'");
				
				$readContratoColeta = read('contrato_coleta',"WHERE id AND inicio<='$data' AND vencimento>='$data' AND id_contrato='$contratoId'");
				if ($readContratoColeta ) {
					foreach($readContratoColeta as $contratoColeta):
					
							$quantidadeContrato=$contratoColeta['quantidade'];
							$valorColetaUnitario=$contratoColeta['valor_unitario'];
							$valorColetaExtra=$contratoColeta['valor_extra'];
							//$valorMinimoMensal = $contratoColeta['valor_mensal'];
					
							// PEGAR DIRETO NO TOPO DE EQUIPAMENTO POR EQUANTO - PATRICIA
							$tipoColeta1=$ordem['tipo_coleta'];
					
					endforeach;
					
					
					// CALCULAR EXTRA
					
					$quantidadeExtra=0;
					$valorExtra =0;
					if($coletaDiaria>$quantidadeContrato){
						$quantidadeExtra = $coletaDiaria-$quantidadeContrato;
					}
					
					$totalColetado=$totalColetado+$coletaDiaria;
					$totalExtra=$totalExtra+$quantidadeExtra;
					
					//Minimo Diário e Minimo Mensal
					if($contrato['cobranca_coleta']=='2'){
						if($coletaDiaria>$quantidadeContrato){
							$valorUnitario = $valorColetaUnitario*$quantidadeContrato;
							$valorExtra = $valorColetaExtra*$quantidadeExtra;
						}else{
							$valorUnitario = $valorColetaUnitario*$quantidadeContrato;
						} // FIM TESTE DE COLETA EXTRA

					}
			
					//Apenas Coletado e Minimo Mensal
					if($contrato['cobranca_coleta']=='1'){
						$valorUnitario = $valorColetaUnitario*$coletaDiaria;
						$minimoTotal = $minimoTotal + $coletaDiaria;
					}
					
					//Apenas Valor Mensal
					if($contrato['cobranca_coleta']=='3'){
						$valorUnitario = $valorColetaUnitario*$coletaDiaria;
						$minimoTotal = $minimoTotal + $coletaDiaria;
					}

					//Apenas Coletado
					if($contrato['cobranca_coleta']=='4'){
						$valorUnitario = $valorColetaUnitario*$coletaDiaria;
						$minimoTotal = $minimoTotal + $coletaDiaria;
					}

					//Quantidade Mínima Mensal
					if($contrato['cobranca_coleta']=='5'){
						$valorUnitario = $valorColetaUnitario*$coletaDiaria;
						$minimoTotal = $minimoTotal + $coletaDiaria;
					}

					// NÃO COLETADO
					if($ordem['nao_coletada']=='1'){
						$coletaDiaria=0;
						$valorUnitario=0;
						$valorExtra=0;
						$statusNome='NÃO COLETADO';
						$ordemTotal = $ordemTotal-1;
					}else{
						// ORDEM ZERADA - cobrar mesmo zerado - 07/10/2018
						if($coletaDiaria=='0'){
							//$ordemTotal = $ordemTotal-1;
						}
					}
					
					// SOMAR TODOS OS VALORES

					$valorTotal=$valorTotal+$valorUnitario+$valorExtra;
					$valorMinimoTotal =  $valorMinimoTotal + $valorUnitario;
					$valorExtraTotal = $valorExtraTotal + $valorExtra;


				}else{

					$quantidadeTotal=0;
					$valorTotal=0;
					$valorMinimoTotal =0;
					$valorExtraTotal = 0;

				} // FIM DO EQUIPAMENTO

			}// FIM ORDEM REALIZADA
		

			endforeach;
		} // FIM ORDEM DE SERVIÇO GERADAS


		// LOCAÇÃO DE EQUIPAMENTOS
		$valorLocacao =0;
		if($contrato['cobrar_locacao']=='1'){
			$valorLocacao = $valorEquipamentoLocacao*$quantidadeContrato;
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

		//Apenas Coletado e Minimo Mensal
		if ( $contrato[ 'cobranca_coleta' ] == '1' ) {
			if ( $valorTotal < $valorMinimoMensal ) {
				$valorTotal = $valorMinimoMensal;
			}
		}

		//Minimo Diário e Minimo Mensal
		if ( $contrato[ 'cobranca_coleta' ] == '2' ) {
			if ( $valorTotal < $valorMinimoMensal ) {
				$valorTotal = $valorMinimoMensal;
			}
		}

		//Apenas Valor Mensal
		if ( $contrato[ 'cobranca_coleta' ] == '3' ) {
			$valorTotal = $valorMinimoMensal;
		}

		//Apenas Coletado
		if ( $contrato[ 'cobranca_coleta' ] == '4' ) {
			//$valorTotal = $valorMinimoMensal;

		}

		//Quantidade Mínima Mensal
		if ( $contrato[ 'cobranca_coleta' ] == '5' ) {
			//$valorTotal = $valorMinimoMensal;
		}
	 
	
	if($valorTotal>0){
		$valorExtrato = $valorTotal + $valorManifesto + $valorLocacao;
	}else{
		$valorExtrato = 0;
	}
	
	// DESCONTO DE PAGAMENTO ANTERIOR ERRADO - 22/06/2020
	$leituraReceber = read('receber',"WHERE id_contrato ='$contratoId' AND desconto_data='$dataExtrato' ORDER BY emissao ASC");

	if ($leituraReceber) {
		foreach($leituraReceber as $receber);
			if($valorTotal>0){
				$valorExtrato = 	$valorExtrato - $receber['desconto_valor'];
			}
	}

	 
		// nao contruir faturamento
		if($contrato['nao_construir_faturamento']=='1'){
			$valorExtrato=0;
		}
	

		if ( $valorExtrato!=0 ) { // SE NAO ZERADO EXTRATO

			// CALCULAR ISS
			$valorIss =0;
			if($contrato['nota_fiscal']=='1'){
				// CALCULAR 5%
				if($contrato['iss_valor']>1){
					$valorIss = ($valorExtrato*$contrato['iss_valor'])/100;
				}else if($contrato['iss_valor']==0){ // ISS ZERADO
					$valorIss = 0;
				}else{
					//Por exemplo: 1000,00 / 0.95 = 1052,63
					// 1.052,63* 5% = 52,63
					$valorIss = $valorExtrato/$contrato['iss_valor'];
					$valorIss = ($valorIss*5)/100;
				}
				$valorExtrato = $valorExtrato + $valorIss ;
			}

			// TAXA DE BOLETO
			$valorBoleto = 0;
			if ( $contrato[ 'boleto_bancario' ] == '1' ) {
				$valorBoleto = $contrato[ 'boleto_valor' ];
				//$valorExtrato = $valorExtrato + $valorBoleto;
			}
			
			if ( $contrato[ 'enviar_boleto_correio' ] == '1' ) {
				$enviar_boleto_correio = $contrato[ 'enviar_boleto_correio' ];
				//$valorExtrato = $valorExtrato + $valorBoleto;
			}else{
				$enviar_boleto_correio = '0';
			}
		}

		//contratos suspensos temporariamente
		if($contrato[ 'status']==19){
			$valorExtrato=0 ;
		}

		// GRAVAR O BOLETO
		if ( $valorExtrato!=0 ) { // SE NAO ZERADO EXTRATO

			$receitaCadastrado = read( 'receber', "WHERE id AND emissao='$faturamento'
											AND id_contrato='$contratoId'" );

			if ( !$receitaCadastrado ) { //VERIFICA DE EXISTE RECEITA 
				
				$contratoTipoId = $contrato[ 'contrato_tipo' ];
				$consultor = $contrato[ 'consultor' ];
				
				$cad[ 'id_contrato' ] = $contratoId;
				$cad[ 'contrato_tipo' ] = $contratoTipoId;
				$cad[ 'consultor' ] = $consultor;
				$cad[ 'id_cliente' ] = $clienteId;
				$cad[ 'emissao' ] = $faturamento;
				$cad[ 'vencimento' ] = $vencimento;
				$cad[ 'valor' ] = $valorExtrato;
				$cad[ 'juros' ] = $valorBoleto;
				$cad[ 'formpag' ] = '1';
				$cad[ 'banco' ] = $bancoExtrato;
				$cad[ 'status' ] = 'Em Aberto';
				$cad[ 'enviar_boleto_correio' ] = $enviar_boleto_correio;
				$cad[ 'observacao' ] = substr( converteData( $faturamento ), 3, 7 );
				
				$cad[ 'remessa_data' ] = $faturamento;
				
				$cad['interacao']= date('Y/m/d H:i:s');
				$cad['data_faturamento']= date('Y/m/d H:i:s');

				// SE NFE GRAVAR A DATA DA EMISSAO 
				if ( $contrato[ 'nota_fiscal' ] == '1' ) {
					// SE MARCADO GERAR NOVA NO FATURAMENTO
					if ( $contrato[ 'nota_no_faturamento' ] == '1' ) {
						$cad['nota_emissao']= $faturamento;
						$cad['link']= '';
					}else{
						$cad['valor_nota_fiscal']= $valorExtrato;
					}	
				}
				
				create( 'receber', $cad );
				
				// INTERAÇÃO
				$interacao='Construção de recebimento automático';
				interacao($interacao,$contratoId);
			}
		} // FIM DA GRAVAÇÃO DO BOLETO


	endforeach; // FIM DOS CONTRATOS

	header( 'Location: painel.php?execute=suporte/faturamento/construir' );
} else {
	header( 'Location: painel.php?execute=suporte/naoencontrado' );

}

?>