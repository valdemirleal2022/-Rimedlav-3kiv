 
<?php
 echo '<script>alert("cronstruindo contrato")</script>';

if (!isset($data1)) {
	header( 'Location: painel.php?execute=suporte/error' );
}
if (!isset($data2)) {
	header( 'Location: painel.php?execute=suporte/error' );
}
if (!isset($bancoExtrato)) {
	header( 'Location: painel.php?execute=suporte/error' );
}

$leitura = read('contrato',"WHERE id AND tipo='2' AND data_suspensao>='$data1' 
											  AND data_suspensao<='$data2' AND status='19' 
											  ORDER BY data_suspensao ASC");

// CONTRATOS
if ( $leitura ) {
	foreach ( $leitura as $contrato ):
	
		// ALTERAÇÃO 28/03/2020

		//$dataSuspensao = $contrato['data_suspensao'];
	 
	//	$dataExtrato=date( "Y-m-d");
	
		// GERANDO DATAS DO EXTRATO
		//$fechamento = $contrato['dia_fechamento'];
//		$vencimento = $contrato['dia_vencimento'];
//
//		// AJUSTADA DATA DE FATURAMENTO E VENCIMENTO
//		$mes = date( 'm', strtotime( $dataExtrato ) );
//		$ano = date( 'Y', strtotime( $dataExtrato ) );
//	
//			// AJUSTADA DATA DE FATURAMENTO E VENCIMENTO
//		$mes = date( 'm', strtotime( $contrato['data_suspensao']) );
//		$ano = date( 'Y', strtotime( $contrato['data_suspensao'] ) );
//
//		$faturamento =  date( "Y-m-d",mktime(0,0,0,$mes,$fechamento,$ano));
//		$vencimento = date( "Y-m-d",mktime(0,0,0,$mes,$vencimento,$ano));
//
//		if($contrato['data_suspensao']>=$faturamento){
//			//16/03/2020 > 10/03/2020
//			$data1 = $faturamento;
//		}else{
//			$data1 = date( "Y-m-d", strtotime( "$faturamento -1 month" ) );
//		}
		
	
		$contratoId = $contrato['id'];
		$dataSuspensao=$contrato['data_suspensao'];
	
		$receber = mostra('receber',"WHERE id_contrato ='$contratoId' AND emissao<'$dataSuspensao' ORDER BY emissao ASC");

		$data1 = $receber['emissao'];
		$data2 = date( "Y-m-d");

		$faturamento = date( "Y-m-d");
		$vencimento = date( "Y-m-d", strtotime( "$faturamento +10 days" ) );


		// CADASTRO DE CLIENTE
		$clienteId = $contrato[ 'id_cliente' ];
	    $contratoTipo = $contrato['contrato_tipo'];
		$cliente = mostra( 'cliente', "WHERE id = '$clienteId'" );

		// FORMA DE COBRANÇA
		$cobrancaId = $contrato[ 'cobranca_coleta' ];
		$cobrancaColeta = mostra( 'contrato_cobranca', "WHERE id AND id='$cobrancaId'" );
	
		$valorEquipamentoLocacao =0;
		$valorMinimoMensal =0;

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
		 }else{

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
							$valorMinimoMensal = $contratoColeta['valor_mensal'];
					
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
						if( $coletaDiaria == 0 ){
							$coletaDiaria = $quantidadeContrato;
						}
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
	
	
		
	
		$apenasValorMensal = $valorMinimoMensal ;

		$valorMinimoMensal = 0;

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
		//	$valorTotal = $apenasValorMensal;
		}

		//Apenas Coletado
		if ( $contrato[ 'cobranca_coleta' ] == '4' ) {
			//$valorTotal = $valorMinimoMensal;

		}

		//Quantidade Mínima Mensal
		if ( $contrato[ 'cobranca_coleta' ] == '5' ) {
			//$valorTotal = $valorMinimoMensal;
		}

		$valorExtrato = $valorTotal + $valorManifesto + $valorLocacao;
	
	
		// nao contruir faturamento
		if($contrato['nao_construir_faturamento']=='1'){
			$valorExtrato=0;
		}
	

		if ( $valorExtrato!=0 ) { // SE NAO ZERADO EXTRATO

			// CALCULAR ISS
			$valorIss = 0;
			if ( $contrato[ 'nota_fiscal' ] == '1' ) {
				// CALCULAR 5%
				if ( $contrato[ 'iss_valor' ] > 1 ) {
					$valorIss = ( $valorExtrato * $contrato[ 'iss_valor' ] ) / 100;
				} else {
					//Por exemplo: 1000,00 / 0.95 = 1052,63
					// 1.052,63* 5% = 52,63
					$valorIss = $valorExtrato / $contrato[ 'iss_valor' ];
					$valorIss = ( $valorIss * 5 ) / 100;
				}
				$valorExtrato = $valorExtrato + $valorIss;
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
	
		// GRAVAR O BOLETO
		if ( $valorExtrato!=0 ) { // SE NAO ZERADO EXTRATO

			//$receitaCadastrado = read( 'receber', "WHERE id AND emissao='$faturamento'										AND id_contrato='$contratoId'" );
			
			$receitaCadastrado = read('receber',"WHERE id_contrato ='$contratoId' AND emissao>='$dataSuspensao'");

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
				$interacao='Construção de recebimento automático - suspenso';
				interacao($interacao,$contratoId);
			}
		} // FIM DA GRAVAÇÃO DO BOLETO

		//break;
	
	endforeach; // FIM DOS CONTRATOS
 
	header("Location: ".$_SESSION['url']);
	
} else {
	
	header( 'Location: painel.php?execute=suporte/naoencontrado' );

}

?>