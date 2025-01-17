 <head>
    <meta charset="iso-8859-1">
</head>

<?php

require_once( "js/fpdf/fpdf.php" );
define( "FPDF_FONTPATH", "font/" );

class RELATORIO extends FPDF {
	function Header() {
		$titulo = SITENOME;
		$this->SetFont( 'Arial', 'B', 12 );
		$titulo = "Extrato Detalhado (Suspenso Temporariamente)";
		$this->Cell( 0, 5, $titulo, 0, 0, 'C' );
		$this->Ln();
	}
	function Footer() {
		$this->SetY( -15 );
		$this->SetFont( 'Arial', 'I', 9 );
		$this->Cell( 0, 10, 'P�gina ' . $this->PageNo() . '/{nb}', 0, 0, 'C' );
	}
}

$pdf=new RELATORIO();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','B',10);
$pdf->Ln();

	

	if(!empty($_GET['contratoId'])){
		$contratoId = $_GET['contratoId'];
	}

	if(!empty($contratoId)){
		$contrato = mostra('contrato',"WHERE id = '$contratoId'");
		if(!$contrato){
			header('Location: painel.php?execute=suporte/error');	
		}
	}

  	//$dataExtrato=date( "Y-m-d");
//
//	// GERANDO DATAS DO EXTRATO 17-03-2020 - SUPENSAO 27/03/2020
//	// COBRAN�A APENAS DE 17/03/2020 ATE 27/03/2020
//
//	$fechamento = $contrato['dia_fechamento']; //10 SUPENSSAO 16
//	$vencimento = $contrato['dia_vencimento']; 
//
//	// AJUSTADA DATA DE FATURAMENTO E VENCIMENTO
//	$mes = date( 'm', strtotime( $dataExtrato ) );
//	$ano = date( 'Y', strtotime( $dataExtrato ) );
//	
//	// AJUSTADA DATA DE FATURAMENTO E VENCIMENTO
//	$mes = date( 'm', strtotime( $contrato['data_suspensao']) );
//	$ano = date( 'Y', strtotime( $contrato['data_suspensao'] ) );
//
//	$faturamento =  date( "Y-m-d",mktime(0,0,0,$mes,$fechamento,$ano));
//	$vencimento = date( "Y-m-d",mktime(0,0,0,$mes,$vencimento,$ano));
//	if($contrato['data_suspensao']>=$faturamento){
//		$data1 = $faturamento;
//	}else{
//		$data1 = date( "Y-m-d", strtotime( "$faturamento -1 month" ) );
//	}

	$dataSuspensao=$contrato['data_suspensao'];
	$receber = mostra('receber',"WHERE id_contrato ='$contratoId' AND emissao<'$dataSuspensao' ORDER BY emissao ASC");

	$data1 = $receber['emissao'];
	$data2 = date( "Y-m-d");

	$faturamento = date( "Y-m-d");
	$vencimento = date( "Y-m-d", strtotime( "$faturamento +10 days" ) );

	// CADASTRO DE CLIENTE
	$clienteId = $contrato['id_cliente'];
	$cliente = mostra('cliente',"WHERE id = '$clienteId'");

	// FORMA DE COBRAN�A
	$cobrancaId = $contrato['cobranca_coleta'];
	$cobrancaColeta = mostra('contrato_cobranca',"WHERE id AND id='$cobrancaId'");

	$pdf->SetX(05);
	$pdf->Cell(10, 5, 'Nome :');
	$pdf->SetX(25);
	$pdf->Cell(10, 5, $cliente['nome']);
	$pdf->SetX(130);
	$pdf->Cell(10, 5, 'Inicio - '.converteData($data1).' || Fim - '.converteData($data2));
	$pdf->ln();

	$pdf->SetX(05);
	$pdf->Cell(10, 5, 'Endere�o :');
	$pdf->SetX(28);
	$pdf->Cell(10, 5, $cliente['endereco'].' '.$cliente['numero'].' '.$cliente['complemento'].' '.$cliente['bairro']);

	$pdf->SetX(150);
	$pdf->Cell(10, 5, 'CNPJ :');
	$pdf->SetX(165);
	$pdf->Cell(10, 5, $cliente['cnpj']);
	$pdf->ln();

	$pdf->SetX(05);
	$pdf->Cell(10, 5, 'Contrato n. :');
	$pdf->SetX(30);
	$pdf->Cell(10, 5, $contrato['id']. '|'. substr($contrato['controle'],0,6));

	$pdf->SetX(65);
	$pdf->Cell(10, 5, 'Faturamento :');
	$pdf->SetX(92);
	$pdf->Cell(10, 5, converteData($faturamento) );

	$pdf->SetX(130);
	$pdf->Cell(10, 5, 'Vencimento :');
	$pdf->SetX(155);
	$pdf->Cell(10, 5, converteData($vencimento) );
	$pdf->ln(); 
	
	$pdf->SetX(05);
	$pdf->Cell(10, 5, 'Forma de Cobran�a : '. $cobrancaColeta['nome']);
	$pdf->SetX(100);
	$pdf->Cell(10, 5, 'Manifesto R$ : '. converteValor($contrato['manifesto_valor']) );

	// FREQUENCIA DA COLETAA
	$frequenciaId = $contrato['frequencia'];
	$frequencia = mostra('contrato_frequencia',"WHERE id AND id='$frequenciaId'");
	$pdf->SetX(160);
	$pdf->Cell(10, 5, 'Frequencia : ' .$frequencia['nome']);


	$pdf->ln();
	$pdf->ln();

	$pdf->SetX(05);
	$pdf->Cell(05, 1, 'Tipo de Coleta');

	$pdf->SetX(49);
	$pdf->Cell(05, 1, 'M�nimo');

	$pdf->SetX(68);
	$pdf->Cell(05, 1, 'Valor Coleta');

	$pdf->SetX(97);
	$pdf->Cell(05, 1, 'Valor Extra');

	$pdf->SetX(125);
	$pdf->Cell(05, 1, 'Valor Mensal');

	$pdf->SetX(160);
	$pdf->Cell(05, 1, 'Inicio');

	$pdf->SetX(180);
	$pdf->Cell(05, 1, 'Vencimento');


	$pdf->ln();

	$contratoId = $contrato['id'];

	$valorEquipamentoLocacao =0;
	$valorMinimoMensal =0;
	$quantidadeMinimoMensal=0;

	//  ABRE APENAS PARA VISUALIZAR NO EXTRATO E PEGAR O MINIMO MENSAL
	$readContratoColeta = read('contrato_coleta',"WHERE id AND inicio<='$faturamento' AND vencimento>'$faturamento' AND id_contrato='$contratoId' ORDER BY vencimento ASC");

	if ( $readContratoColeta ) {
		foreach($readContratoColeta as $contratoColeta):
	
				$pdf->ln();
		
				// PEGAR TIPO DE EQUIPAMENTO E MINIMO MENSAL
				$tipoEquipamento=$contratoColeta['tipo_coleta'];
				$tipoColeta = mostra('contrato_tipo_coleta',"WHERE id ='$tipoEquipamento'");
			
				$pdf->SetX(05);
				$pdf->Cell(05,05, $tipoColeta['nome']);

				$valorMinimoMensal = $contratoColeta['valor_mensal'];
				$valorEquipamentoLocacao = $tipoColeta['valor_locacao'];
	
				$pdf->SetX(54);
				$pdf->Cell(05,05, $contratoColeta['quantidade']);

				$pdf->SetX(75);
				$pdf->Cell(05,05, converteValor( $contratoColeta['valor_unitario']) );

				$pdf->SetX(100);
				$pdf->Cell(05,05, converteValor($contratoColeta['valor_extra']) );
					
				$pdf->SetX(135);
				$pdf->Cell(05,05, converteValor($contratoColeta['valor_mensal']) );
		
				$pdf->SetX(156);
				$pdf->Cell(05,05,  converteData($contratoColeta['inicio']) );
		
				$pdf->SetX(182);
				$pdf->Cell(05,05,  converteData($contratoColeta['vencimento']) );
		
				
		endforeach;
	 }else{
		$pdf->ln(5);
	}

	$apenasValorMensal=$valorMinimoMensal;
	$valorMinimoMensal = 0;

	$pdf->ln();
	$pdf->ln();

	$pdf->SetX(05);
	$pdf->Cell(05, 1, 'Data');

	$pdf->SetX(22);
	$pdf->Cell(05, 1, 'N�mero');

	$pdf->SetX(40);
	$pdf->Cell(05, 1, 'Equipamento');

	$pdf->SetX(75);
	$pdf->Cell(05, 1, 'Coletado');

	$pdf->SetX(93);
	$pdf->Cell(20, 1, 'Extra');

	$pdf->SetX(108);
	$pdf->Cell(05, 1, 'Valor Coleta');

	$pdf->SetX(133);
	$pdf->Cell(05, 1, 'Valor Extra');

	$pdf->SetX(160);
	$pdf->Cell(05, 1, 'Total');

	$pdf->SetX(175);
	$pdf->Cell(05, 1, 'Status' );

	$pdf->ln();

	$pdf->SetFont('Arial','B',10);
	$pdf->SetX(5);
	$pdf->Cell(5,5,'___________________________________________________________________________________________________');
	$pdf->ln();


	$ii=0;


	// FUNCAO DO EXTRATO IGUAL A CONSTRU��O DO BOLETO

	// INICIALIZA COM TOTAL ZERADOS
	$ordemTotal=0;
	$minimoTotal=0;
	$extraTotal=0;

	$valorMinimoTotal=0;
	$valorExtraTotal=0;
	$valorTotal=0;

	// ORDEM DE SERVI�O GERADAS
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
			
			//  ****************** GRAVA O TIPO DE COLETA POR ENQUANTO ****************
			if(empty($tipoColeta)){
				$cad['tipo_coleta1']= $tipoEquipamento;
				update('contrato_ordem',$cad,"id = '$ordemId'");
			}
		
			// INICIALIZA ZERADO O VALOR UNITARIO E EXTRA DA COLETA
			$coletaDiaria = 0 ;
			$valorColetaUnitario =0;
			$quantidadeExtra=0;
			$valorColetaExtra = 0;
		
			// SE REALIZADA A ORDEM DE SERVI�O
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
					
					//Minimo Di�rio e Minimo Mensal
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
						
						if($coletaDiaria==0){
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

					// N�O COLETADO
					if($ordem['nao_coletada']=='1'){
						$coletaDiaria=0;
						$valorUnitario=0;
						$valorExtra=0;
						$statusNome='N�O COLETADO';
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

		
			$pdf->SetFont('Arial','B',8);
			$pdf->SetX(05);
			$pdf->Cell(05, 5, converteData($ordem['data']));

			$pdf->SetX(27);
			$pdf->Cell(10, 5, $ordem['id'],0,0,'R');

			$pdf->SetX(40);
			$pdf->Cell(05, 5, $tipoColetaNome);
		
			$pdf->SetX(78);
			$pdf->Cell(10, 5, $coletaDiaria,0,0,'R');

			$pdf->SetX(93);
			$pdf->Cell(10, 5, $quantidadeExtra,0,0,'R');

			$pdf->SetX(115);
			$pdf->Cell(10, 5,converteValor($valorUnitario),0,0,'R');

			$pdf->SetX(140);
			$pdf->Cell(10, 5,converteValor($valorExtra),0,0,'R');

			$pdf->SetX(160);
			$pdf->Cell(10, 5, converteValor($valorUnitario+$valorExtra),0,0,'R');
			
			$pdf->SetX(175);
			$pdf->Cell(05,05, $statusNome);

			$pdf->ln();

			// CRICAR NOVA PAGINA
			$ii=$ii+1;
			if ($ii>35){
				$pdf->AddPage();
				$ii=1;
			}

		endforeach;
	} // FIM ORDEM DE SERVI�O GERADAS


	// LINHA
	$pdf->SetFont('Arial','B',10);

	$pdf->SetX(5);
	$pdf->Cell(5,5,'___________________________________________________________________________________________________');
	$pdf->ln(5);

	$pdf->SetX(05);
	$pdf->Cell(10, 5, 'Total');
	$pdf->SetX(27);
	$pdf->Cell(10, 5, $ordemTotal,0,0,'R');
	$pdf->SetX(73);
	$pdf->Cell(10, 5, $totalColetado,0,0,'R');
	$pdf->SetX(93);
	$pdf->Cell(10, 5, $totalExtra,0,0,'R');
	$pdf->SetX(115);
	$pdf->Cell(10, 5, converteValor($valorMinimoTotal),0,0,'R');
	$pdf->SetX(140);
	$pdf->Cell(10, 5, converteValor($valorExtraTotal),0,0,'R');
	$pdf->SetX(160);
	$pdf->Cell(10, 5, converteValor($valorTotal),0,0,'R');
	$pdf->ln(5);

	// FIM DO DETALHAMENTO DAS ORDENS

	//Apenas Coletado e Minimo Mensal
	if($contrato['cobranca_coleta']=='1'){
		if($valorTotal<$valorMinimoMensal){
			$valorTotal = $valorMinimoMensal;
		}
	}

	//Minimo Di�rio e Minimo Mensal
	if($contrato['cobranca_coleta']=='2'){
		if($valorTotal<$valorMinimoMensal){
			$valorTotal = $valorMinimoMensal;
		}
	}

	//Apenas Valor Mensal
	if($contrato['cobranca_coleta']=='3'){
		//$valorTotal = $apenasValorMensal;
	}

	//Apenas Coletado
	if($contrato['cobranca_coleta']=='4'){
		//$valorTotal = $valorMinimoMensal;

	}

	//Quantidade M�nima Mensal
	if($contrato['cobranca_coleta']=='5'){
		if($totalColetado>$quantidadeMinimoMensal){
			$extra = $totalColetado- $quantidadeMinimoMensal;
			$valorExtra=$extra*$contratoColeta['valor_extra'];
			$valorTotal=$valorMinimoMensal+$valorExtra;
		}else{
			$valorTotal = $apenasValorMensal;
		}
	}
	

	// LINHA
	$pdf->SetFont('Arial','B',10);
	$pdf->SetX(5);
	$pdf->Cell(5,5,'___________________________________________________________________________________________________');
	$pdf->ln(5);

	$pdf->SetX(05);
	$pdf->Cell(10, 5, 'Valor Total R$ :');
	$pdf->SetX(40);
	$pdf->Cell(10, 5, converteValor($valorTotal) );

	$pdf->SetX(62);
	$pdf->Cell(10, 5, 'M�nimo Mensal R$ :');
	$pdf->SetX(105);
	$pdf->Cell(10, 5, converteValor($valorMinimoMensal) );

	$pdf->SetX(130);
	$pdf->Cell(10, 5, 'Quantidade M�nimo Mensal :');
	$pdf->SetX(185);
	$pdf->Cell(10, 5,$quantidadeMinimoMensal );
	$pdf->ln(7);
	
	// LOCA��O DE EQUIPAMENTOS
	$valorLocacao =0;
	if($contrato['cobrar_locacao']=='1'){
		$valorLocacao = $valorEquipamentoLocacao*$quantidadeContrato;
	}

	$pdf->SetX(05);
	$pdf->Cell(10, 5, 'Loca�ao/Manuten�ao R$ :');
	$pdf->SetX(55);
	$pdf->Cell(10, 5, converteValor($valorLocacao) );
	
	// COBRA MANIFESTO
	$valorManifesto=0;
	if($contrato['manifesto']=='1'){
		$valorManifesto = $contrato['manifesto_valor']*$ordemTotal;
	}

	// COBRA MANIFESTO MESMO QUE DESMARCADO
	if($contrato['manifesto_valor']>'0'){
		$valorManifesto = $contrato['manifesto_valor']*$ordemTotal;
	}

	$pdf->SetX(80);
	$pdf->Cell(10, 5, 'Manifesto R$ :');
	$pdf->SetX(110);
	$pdf->Cell(10, 5, converteValor($valorManifesto) );
	$pdf->ln(7);


	$valorExtrato = $valorTotal + $valorManifesto + $valorLocacao;

	$pdf->SetX(05);
	$pdf->Cell(10, 5, 'Base de Calculo R$ :');
	$pdf->SetX(50);
	$pdf->Cell(10, 5, converteValor($valorExtrato) );

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

	$pdf->SetX(82);
	$pdf->Cell(10, 5, 'ISS R$ :');
	$pdf->SetX(105);
	$pdf->Cell(10, 5, converteValor($valorIss) );

	$pdf->SetX(140);
	$pdf->Cell(10, 5, 'Total da NFe R$  :');
	$pdf->SetX(180);
	$pdf->Cell(10, 5, converteValor($valorExtrato) );
	$pdf->ln(7);

	// TAXA DE BOLETO
	$valorBoleto =0;
	if($contrato['boleto_bancario']=='1'){
		$valorBoleto = $contrato['boleto_valor'];
		$valorExtrato=$valorExtrato+$valorBoleto;
	}

	$pdf->SetX(05);
	$pdf->Cell(10, 5, 'Taxa Banc�ria R$ :');
	$pdf->SetX(40);
	$pdf->Cell(10, 5, converteValor($valorBoleto) );
	
	$pdf->SetX(140);
	$pdf->Cell(10, 5, 'Valor  Boleto R$ :');
	$pdf->SetX(180);
	$pdf->Cell(10, 5, converteValor($valorExtrato) );
	$pdf->ln(5);

	// LINHA
	$pdf->SetFont('Arial','B',10);
	$pdf->SetX(5);
	$pdf->Cell(5,5,'___________________________________________________________________________________________________');

	//fim do extrato

ob_clean();  
$pdf->Output('relatorio.pdf', 'I');
 
?>