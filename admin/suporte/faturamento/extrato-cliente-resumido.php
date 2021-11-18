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
		$titulo = "Extrato do Cliente";
		$this->Cell( 0, 5, $titulo, 0, 0, 'C' );
		$this->Ln();
	}
	function Footer() {
		$this->SetY( -15 );
		$this->SetFont( 'Arial', 'I', 9 );
		$this->Cell( 0, 10, 'Página ' . $this->PageNo() . '/{nb}', 0, 0, 'C' );
	}
}

	$pdf=new RELATORIO();
	$pdf->AliasNbPages();
	$pdf->AddPage();
	$pdf->SetFont('Arial','B',10);
	$pdf->Ln();

	$dataExtrato=$_SESSION['dataExtrato'];

	if(!empty($_GET['contratoId'])){
		$contratoId = $_GET['contratoId'];
	}

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
	$clienteId = $contrato['id_cliente'];
	$cliente = mostra('cliente',"WHERE id = '$clienteId'");

	// FORMA DE COBRANÇA
	$cobrancaId = $contrato['cobranca_coleta'];
	$cobrancaColeta = mostra('contrato_cobranca',"WHERE id AND id='$cobrancaId'");

	$pdf->SetX(05);
	$pdf->Cell(10, 5, 'Nome :');
	$pdf->SetX(25);
	$pdf->Cell(10, 5, $cliente['nome']);

	$pdf->SetX(140);
	$pdf->Cell(10, 5, 'Inicio : '.converteData($data1).' || Fim : '.converteData($data2));
	$pdf->ln();

	$pdf->SetX(05);
	$pdf->Cell(10, 5, 'Endereço :');
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

	$pdf->ln();
	
	$pdf->SetX(05);
	$pdf->Cell(05, 1, 'Data');

	$pdf->SetX(22);
	$pdf->Cell(05, 1, 'Número');

	$pdf->SetX(40);
	$pdf->Cell(05, 1, 'Equipamento');

	$pdf->SetX(95);
	$pdf->Cell(05, 1, 'Coletado');

	$pdf->SetX(175);
	$pdf->Cell(05, 1, 'Status' );

	$pdf->ln();

	$pdf->SetFont('Arial','B',10);
	$pdf->SetX(5);
	$pdf->Cell(5,5,'___________________________________________________________________________________________________');
	$pdf->ln();


	$ii=0;

	
	// CONTRATO SUSPENSO TEMPORARIAMENTE
	$hoje=date( "Y-m-d");
	$contratoBaixa = read('contrato_baixa',"WHERE id_contrato ='$contratoId' AND tipo='8'");
	if($contratoBaixa ){
		$dataSuspensao=$contrato['data_suspensao'];
		$receber = mostra('receber',"WHERE id_contrato ='$contratoId' AND emissao<='$hoje' ORDER BY emissao ASC");
		$data1 = $receber['emissao'];
		$valorMinimoMensal = 0;
	}
	
	// CONTRATO SUSPENSO SO CONSIDER QUANTIDADE COLETADA
	if($contrato['status']==6){
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

		
			$pdf->SetFont('Arial','B',8);
			$pdf->SetX(05);
			$pdf->Cell(05, 5, converteData($ordem['data']));

			$pdf->SetX(27);
			$pdf->Cell(10, 5, $ordem['id'],0,0,'R');

			$pdf->SetX(40);
			$pdf->Cell(05, 5, $tipoColetaNome);
		
			$pdf->SetX(95);
			$pdf->Cell(10, 5, $coletaDiaria,0,0,'R');

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
	} // FIM ORDEM DE SERVIÇO GERADAS


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
 
	$pdf->ln(5);

	// FIM DO DETALHAMENTO DAS ORDENS

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
	

	// LINHA
	$pdf->SetFont('Arial','B',10);
	$pdf->SetX(5);
	$pdf->Cell(5,5,'___________________________________________________________________________________________________');

	//fim do extrato

ob_clean();  
$pdf->Output('relatorio.pdf', 'I');
 
?>