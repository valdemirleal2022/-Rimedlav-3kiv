<head>
    <meta charset="iso-8859-1">
</head>



<?php

	require_once( "fpdf/fpdf.php" );
	define( "FPDF_FONTPATH", "font/" );

	class RELATORIO extends FPDF {
		function Header() {
			$titulo = SITENOME;
			$this->SetFont( 'Arial', 'B', 12 );
			$this->Ln();
		}
	}

	$pdf=new RELATORIO();
	$pdf->AliasNbPages();
	$pdf->AddPage();
	$pdf->SetFont('Arial','B',8);
	$pdf->Ln();


	if(!empty($_GET['rps'])){
    	$boletoId = $_GET['rps'];
	}
	   
	$boleto = mostra('receber',"WHERE id = '$boletoId'");
	if(!$boleto){
		echo '<div class="msgError">Extrato Não Encontrado !</div> <br />';
		header('Location: painel.php?execute=suporte/error');	
	}

	$dataExtrato=$boleto['emissao'];

	$contratoId = $boleto['id_contrato'];
	$contrato = mostra('contrato',"WHERE id = '$contratoId'");
	if(!empty($contratoId)){
		$contrato = mostra('contrato',"WHERE id = '$contratoId'");
		if(!$contrato){
			header('Location: painel.php?execute=suporte/error');	
		}
	}

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

	// FEVEREIRO AJUSTA DIA 28
	if($mesFaturamento=='02' AND $dia=='28'){
		$data1 = date( "Y-m-d", strtotime( "$faturamento -1 month" ) );
	}

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
		$data2 = date( "Y-m-d", strtotime( "$faturamento" ) );
	}
		

	// MARÇO AJUSTA DIA 30
	if($mesFaturamento=='03' AND $dia=='30'){
		$data1 = date( "Y-m-d", strtotime( "$faturamento -1 month" ) );
		$data1 = date( "Y-m-d", strtotime( "$data1 -1 days" ) );
		$data2 = date( "Y-m-d", strtotime( "$faturamento" ) );
	}

	$pdf->SetFont('Arial','B',8);
	
	$pdf->Cell(140,5,' ','LTR',0,'L',0);   // empty cell with left,top, and right borders
	$pdf->Cell(50,5,'N. RPS',1,0,'L',0);

					$pdf->Ln();

	$pdf->Cell(140,5,'Recibo Provisorio de Serviços - RPS','LR',0,'C',0);  // cell with left and right borders
	$pdf->Cell(50,5,$boleto['id'],1,0,'L',0);
	
					$pdf->Ln();

	$pdf->Cell(140,5,'','LR',0,'C',0);  // cell with left and right borders
	$pdf->Cell(50,5,'Data e Hora',1,0,'L',0);

					$pdf->Ln();

	$pdf->Cell(140,5,'','LR',0,'C',0);  // cell with left and right borders
	$pdf->Cell(50,5,date('d/m/Y H:i:s') ,'LRB',0,'L',0);

					$pdf->Ln();

	$pdf->Cell(140,5,'','LR',0,'C',0);  // cell with left and right borders
	$pdf->Cell(50,5,'Código de Verificação',1,0,'L',0);

					$pdf->Ln();

		$aleatorio = rand(9, 10); // 5 À 10 CARACTERES
		$valor = substr(str_shuffle("AaBbCcDdEeFfGgHhIiJjKkLlMmNnPpQqRrSsTtUuVvYyXxWwZz0123456789"), 0, $aleatorio);

	$pdf->Cell(140,5,'','LR',0,'L',0);   // empty cell with left,bottom, and right borders
	$pdf->Cell(50,5, $valor ,'LRB',0,'L',0);

					$pdf->Ln();

	$pdf->Cell(140,5,'','LR',0,'C',0);  // cell with left and right borders
	$pdf->Cell(50,5,'N. Nota Fiscal',1,0,'L',0);

					$pdf->Ln();

	$pdf->Cell(140,5,'','LBR',0,'L',0);   // empty cell with left,bottom, and right borders
	$pdf->Cell(50,5, $boleto['id'] ,'LRB',0,'L',0);

					$pdf->Ln();

	$pdf->Cell(190,5,'PRESTADOR DE SERVIÇOS','LR',0,'C',0);  // borda direita e esquerda
	
					$pdf->Ln();

	$empresa = mostra('empresa',"WHERE id");
	
	$pdf->Cell(190,5,'','LR',0,'C',0);  // LINHA DIREITA E ESQUERDA

	$pdf->SetX(30);
	$pdf->Cell(10, 5, 'CNPJ : '.$empresa['cnpj'] );

	$pdf->SetX(90);
	$pdf->Cell(10, 5, 'Inscrição Municipal : ' . $empresa['inscricao'] );
			
					$pdf->Ln();

	$pdf->Cell(190,5,'','LR',0,'C',0);  // LINHA DIREITA E ESQUERDA
	$pdf->SetX(30);
	$pdf->Cell(10, 5, 'Nome/Razão Social : ' . $empresa['nome'] );

					$pdf->ln();

	$pdf->Cell(190,5,'','LR',0,'C',0);  // LINHA DIREITA E ESQUERDA	
	$pdf->SetX(30);
	$pdf->Cell(10, 5, 'Endereço : '.$empresa['endereco'].' '.$empresa['bairro'].' '.$empresa['cidade']);

					$pdf->ln();

	$pdf->Cell(190,5,'','LR',0,'C',0);  // LINHA DIREITA E ESQUERDA
	$pdf->SetX(30);
	$pdf->Cell(10, 5, 'Email : '.$empresa['email']);

					$pdf->Ln();

	$pdf->Cell(190,5,'','LBR',0,'L',0);   // linha da direita e esquerda e abaixo

					$pdf->Ln();

	$pdf->Cell(190,5,'TOMADOR DE SERVIÇOS','LR',0,'C',0);  // borda direita e esquerda
	
					$pdf->Ln();

	// CADASTRO DE CLIENTE
	$clienteId = $contrato['id_cliente'];
	$cliente = mostra('cliente',"WHERE id = '$clienteId'");

	$pdf->Cell(190,5,'','LR',0,'C',0);  // LINHA DIREITA E ESQUERDA

	$pdf->SetX(15);
	$pdf->Cell(10, 5, 'Nome : '. $cliente['nome']);

	$pdf->SetX(150);
	$pdf->Cell(10, 5, 'CNPJ : ' . $cliente['cnpj']);

					$pdf->ln();

	$pdf->Cell(190,5,'','LR',0,'C',0);  // LINHA DIREITA E ESQUERDA
	$pdf->SetX(15);
	$pdf->Cell(10, 5, 'Endereço : '. $cliente['endereco'].' '.$cliente['numero'].' '.$cliente['complemento'].' '.$cliente['bairro']);

					$pdf->ln();

	$pdf->Cell(190,5,'','LBR',0,'L',0);   // linha da direita e esquerda e abaixo

					$pdf->ln();

	$pdf->Cell(190,5,'','LR',0,'C',0);  // LINHA DIREITA E ESQUERDA


					$pdf->ln();

	$pdf->Cell(190,5,'DISCRIMINAÇÃO DOS SERVIÇOS','LR',0,'C',0);  // borda direita e esquerda
		
					$pdf->ln();

	$pdf->Cell(190,5,'','LR',0,'C',0);  // LINHA DIREITA E ESQUERDA
	
	$pdf->ln();

	$pdf->Cell(190,5,'','LR',0,'C',0);  // LINHA DIREITA E ESQUERDA

	$contratoId = $contrato['id'];

	$valorEquipamentoLocacao =0;
	$valorMinimoMensal =0;
	$quantidadeMinimoMensal=0;

	//  ABRE APENAS PARA VISUALIZAR NO EXTRATO E PEGAR O MINIMO MENSAL
	$readContratoColeta = read('contrato_coleta',"WHERE id AND inicio<='$faturamento' AND vencimento>'$faturamento' AND id_contrato='$contratoId' ORDER BY vencimento ASC");

	if ( $readContratoColeta ) {
		foreach($readContratoColeta as $contratoColeta):
	
				// PEGAR TIPO DE EQUIPAMENTO E MINIMO MENSAL
				$tipoEquipamento=$contratoColeta['tipo_coleta'];
				$tipoColeta = mostra('contrato_tipo_coleta',"WHERE id ='$tipoEquipamento'");
		
				
				$valorMinimoMensal = $contratoColeta['valor_mensal'];
				$valorEquipamentoLocacao = $tipoColeta['valor_locacao'];
	
				
	
				$tipoResiduoId=$tipoColeta['residuo'];
				$tipoResiduo= mostra('contrato_tipo_residuo',"WHERE id ='$tipoResiduoId'");

				$tipoResiduoNome='Coleta de Resíduo : '.$tipoResiduo['nome'];

				if($contrato['contrato_tipo']>7){
					$tipoResiduoNome='Coleta de Resíduo : Infectante';
				}
		
				$pdf->SetX(15);
				$pdf->Cell(05,05, $tipoResiduoNome );
			
		endforeach;

	}


	$pdf->ln();

	$pdf->Cell(190,5,'','LR',0,'C',0);  // LINHA DIREITA E ESQUERDA

	$pdf->ln();

	$pdf->Cell(190,5,'','LR',0,'C',0);  // LINHA DIREITA E ESQUERDA

	$pdf->ln();

	$pdf->Cell(190,5,'','LR',0,'C',0);  // LINHA DIREITA E ESQUERDA

	$pdf->ln();

	$pdf->Cell(190,5,'','LR',0,'C',0);  // LINHA DIREITA E ESQUERDA

	$pdf->ln();

	$pdf->Cell(190,5,'','LR',0,'C',0);  // LINHA DIREITA E ESQUERDA

	$pdf->ln();

	$pdf->Cell(190,5,'','LR',0,'C',0);  // LINHA DIREITA E ESQUERDA

	$pdf->ln();

	$pdf->Cell(190,5,'','LR',0,'C',0);  // LINHA DIREITA E ESQUERDA

	$pdf->ln();

	$pdf->Cell(190,5,'','LR',0,'C',0);  // LINHA DIREITA E ESQUERDA

	$pdf->ln();

	$pdf->Cell(190,5,'','LBR',0,'L',0);   // linha da direita e esquerda e abaixo

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
					
							// PEGAR DIRETO NO TOPO DE EQUIPAMENTO POR EQUANTO - PATRICIA
							$tipoColeta1=$ordem['tipo_coleta'];
							$quantidadeMinimoMensal=$contratoColeta['quantidade_mensal'];
					
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



	//Apenas Coletado e Minimo Mensal
	if($contrato['cobranca_coleta']=='1'){
		if($valorTotal<$valorMinimoMensal){
			$valorTotal = $valorMinimoMensal;
		}
	}

	//Minimo Diário e Minimo Mensal
	if($contrato['cobranca_coleta']=='2'){
		if($valorTotal<$valorMinimoMensal){
			$valorTotal = $valorMinimoMensal;
		}
	}

	//Apenas Valor Mensal
	if($contrato['cobranca_coleta']=='3'){
		$valorTotal = $valorMinimoMensal;
	}

	//Apenas Coletado
	if($contrato['cobranca_coleta']=='4'){
		//$valorTotal = $valorMinimoMensal;

	}

	//Quantidade Mínima Mensal
	if($contrato['cobranca_coleta']=='5'){
		if($totalColetado>$quantidadeMinimoMensal){
			$extra = $totalColetado- $quantidadeMinimoMensal;
			$valorExtra=$extra*$contratoColeta['valor_extra'];
			$valorTotal=$valorMinimoMensal+$valorExtra;
		}else{
			$valorTotal = $valorMinimoMensal;
		}
	}
	

	
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


	$valorExtrato = $valorTotal + $valorManifesto + $valorLocacao;


	// CALCULAR ISS
	$valorIss =0;
	if($contrato['nota_fiscal']=='1'){
		// CALCULAR 5%
		if($contrato['iss_valor']>1){
			$valorIss = ($valorExtrato*$contrato['iss_valor'])/100;
		}else{
			//Por exemplo: 1000,00 / 0.95 = 1052,63
			// 1.052,63* 5% = 52,63
			$valorIss = $valorExtrato/$contrato['iss_valor'];
			$valorIss = ($valorIss*5)/100;
		}
		$valorExtrato = $valorExtrato + $valorIss ;
	}


					$pdf->ln();

	$pdf->Cell(190,5,'','LR',0,'C',0);  // LINHA DIREITA E ESQUERDA

					$pdf->ln();

	$pdf->Cell(190,5,'','LR',0,'C',0);  // LINHA DIREITA E ESQUERDA
	
	$pdf->SetX(60);
	$pdf->Cell(10, 5, 'Valor do RPS R$ : ' . converteValor($valorExtrato) );

						$pdf->ln();

	$pdf->Cell(190,5,'','LBR',0,'L',0);   // linha da direita e esquerda e abaixo

						$pdf->ln();

	$pdf->Cell(30,5,'Deduções  (R$)','LR',0,'C',0);  // LINHA DIREITA E ESQUERDA

	$pdf->Cell(30,5,'Desconto  (R$)','LR',0,'C',0);  // LINHA DIREITA E ESQUERDA
	
	$pdf->Cell(30,5,'Base de Calculo  (R$)','LR',0,'C',0);  // LINHA DIREITA E ESQUERDA

	$pdf->Cell(30,5,'Aliquota (%)','LR',0,'C',0);  // LINHA DIREITA E ESQUERDA

	$pdf->Cell(35,5,'Valor do ISS (R$)','LR',0,'C',0);  // LINHA DIREITA E ESQUERDA

	$pdf->Cell(35,5,'Crédito IPTU (R$)','LR',0,'C',0);  // LINHA DIREITA E ESQUERDA

						$pdf->ln();

	$pdf->Cell(30,5,'0.00','LBR',0,'C',0);  // LINHA DIREITA E ESQUERDA

	$pdf->Cell(30,5,'0.00','LBR',0,'C',0);  // LINHA DIREITA E ESQUERDA

	$pdf->Cell(30,5,converteValor($valorExtrato),'LBR',0,'C',0);  // LINHA DIREITA E ESQUERDA

	$pdf->Cell(30,5,'5%','LBR',0,'C',0);  // LINHA DIREITA E ESQUERDA

	$pdf->Cell(35,5,converteValor($valorIss),'LBR',0,'C',0);  // LINHA DIREITA E ESQUERDA

	$pdf->Cell(35,5,'0.00','LBR',0,'C',0);  // LINHA DIREITA E ESQUERDA

						$pdf->ln();

	$pdf->Cell(190,5,'OUTRAS INFORMAÇÕES','LR',0,'C',0);  // borda direita e esquerda
		
						$pdf->ln();
	$pdf->Cell(190,5,'','LR',0,'C',0);  // borda direita e esquerda
	
						$pdf->ln();

	$pdf->Cell(190,5,'','LR',0,'C',0);  // borda direita e esquerda
	
						$pdf->ln();

	$pdf->Cell(190,5,'','LBR',0,'L',0);   // linha da direita e esquerda e abaixo

ob_clean();  
$pdf->Output('relatorio.pdf', 'I');
 
?>