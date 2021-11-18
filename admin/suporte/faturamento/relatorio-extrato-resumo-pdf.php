<head>
    <meta charset="iso-8859-1">
</head>


<?php

require_once("js/fpdf/fpdf.php");
define("FPDF_FONTPATH","font/");

$dia = $_SESSION[ 'dia' ];
$dataExtrato=$_SESSION['dataExtrato'];
$contratoTipo=$_SESSION['contratoTipo'];
$status=$_SESSION['status'];

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

class RELATORIO extends FPDF{
    function Header(){
	  $titulo=SITENOME;
      $this->SetFont('Arial','I',8);
      $this->Cell(0,5,$titulo,0,0,'L');
      $data=date('d/m/Y H:i:s');
      $this->SetFont('Arial','I',8);
      $this->Cell(0,5,$data,0,0,'R'); 	  
	  $this->SetFont('Arial','B',12);
	  $titulo="ORDEM DE SERVIÇO";
      $this->Ln(8);
	  $this->Cell(0,5,$titulo,0,0,'C'); 
      $this->Ln(10);
    }
    function Footer(){
      $this->SetY(-15);
      $this->SetFont('Arial','I',9);
      $this->Cell(0,10,'Página '.$this->PageNo().'/{nb}',0,0,'C');
    }
}

$pdf= new fpdf("P","mm","Letter");
$pdf->AddPage();
$pdf->SetMargins(10, 10, 5, 5);
$pdf->SetFont('Arial','I',9);
$pdf->SetX(140);
$pdf->Cell(10, 5, 'Data Extrato : '.converteData($dataExtrato));

$i=1;

$totalFaturar=0;
$totalFaturado=0;

foreach($leitura as $contrato):

	if ($i>5){
		$pdf->AddPage();
		$i=1;
	}

	$pdf->SetFont('Arial','B',10);

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

	$clienteId = $contrato['id_cliente'];
	$cliente = mostra('cliente',"WHERE id = '$clienteId'");
	$pdf->ln(1);	
	// LINHA
	$pdf->SetFont('Arial','B',8);
	$pdf->SetX(5);
	$pdf->Cell(5,5,'_____________________________________________________________________________________________________________________________');
	$pdf->ln(7);

	$pdf->SetX(05);
	$pdf->Cell(10, 5, 'Nome :');
	$pdf->SetX(25);
	$pdf->Cell(10, 5, $cliente['nome']);
	$pdf->SetX(140);
	$pdf->Cell(10, 5, 'Inicio : '.converteData($data1).' || Fim : '.converteData($data2));

	$pdf->ln(4);

	$pdf->SetX(05);
	$pdf->Cell(10, 5, 'Endereço :');
	$pdf->SetX(28);
	$pdf->Cell(10, 5, $cliente['endereco'].' '.$cliente['numero'].' '.$cliente['complemento'].' '.$cliente['bairro']);

	$pdf->SetX(150);
	$pdf->Cell(10, 5, 'CNPJ :');
	$pdf->SetX(165);
	$pdf->Cell(10, 5, $cliente['cnpj']);

	$pdf->ln(4);

	$pdf->SetX(05);
	$pdf->Cell(10, 5, 'Contrato n. :');
	$pdf->SetX(30);
	$pdf->Cell(10, 5, $contrato['id']);

	$pdf->SetX(65);
	$pdf->Cell(10, 5, 'Faturamento :');
	$pdf->SetX(92);
	$pdf->Cell(10, 5, converteData($faturamento) );

	$pdf->SetX(130);
	$pdf->Cell(10, 5, 'Vencimento :');
	$pdf->SetX(155);
	$pdf->Cell(10, 5, converteData($vencimento) );

	$pdf->ln(4);

	$cobrancaId = $contrato['cobranca_coleta'];
	$cobrancaColeta = mostra('contrato_cobranca',"WHERE id AND id='$cobrancaId'");

	$pdf->SetX(05);
	$pdf->Cell(10, 5, 'Forma de Cobrança : '. $cobrancaColeta['nome']);
	$pdf->SetX(100);
	$pdf->Cell(10, 5, 'Manifesto R$ : '. converteValor($contrato['manifesto_valor']) );

	// FREQUENCIA DA COLETAA
	$frequenciaId = $contrato['frequencia'];
	$frequencia = mostra('contrato_frequencia',"WHERE id AND id='$frequenciaId'");
	$pdf->SetX(160);
	$pdf->Cell(10, 5, 'Frequencia : ' .$frequencia['nome']);

	$pdf->ln(6);

	$pdf->SetX(05);
	$pdf->Cell(05, 1, 'Tipo de Coleta');

	$pdf->SetX(40);
	$pdf->Cell(05, 1, 'Mínimo');

	$pdf->SetX(60);
	$pdf->Cell(05, 1, 'Valor Coleta');

	$pdf->SetX(92);
	$pdf->Cell(05, 1, 'Valor Extra');

	$pdf->SetX(120);
	$pdf->Cell(05, 1, 'Valor Mensal');

	$pdf->SetX(160);
	$pdf->Cell(05, 1, 'Inicio');

	$pdf->SetX(180);
	$pdf->Cell(05, 1, 'Vencimento');

	$pdf->ln(1);
	
	$contratoId = $contrato['id'];

	//  ABRE APENES PARA VISUALIZAR NO EXTRATO
	$leituraContratoColeta = read('contrato_coleta',"WHERE id AND inicio<='$data1' 
								AND vencimento>='$data2' AND id_contrato='$contratoId'");
	if ( $leituraContratoColeta ) {
		foreach($leituraContratoColeta as $contratoColeta):
		
				// PEGAR TIPO DE EQUIPAMENTO E MINIMO MENSAL

				$tipoColeta=$contratoColeta['tipo_coleta'];
				$minimoDiario=$contratoColeta['quantidade'];
				$valorUnitario=$contratoColeta['valor_unitario'];
				$valorExtra=$contratoColeta['valor_extra'];
				$valorMinimoMensal = $contratoColeta['valor_mensal'];
				$tipoColeta = mostra('contrato_tipo_coleta',"WHERE id ='$tipoColeta'");
		
				$pdf->SetX(05);
				$pdf->Cell(05,05, $tipoColeta['nome']);

				$valorLocacao=$tipoColeta['valor_locacao'];

				$pdf->SetX(45);
				$pdf->Cell(05,05, $minimoDiario);

				$pdf->SetX(68);
				$pdf->Cell(05,05, converteValor($valorUnitario) );

				$pdf->SetX(100);
				$pdf->Cell(05,05, converteValor($valorExtra) );

				$pdf->SetX(130);
				$pdf->Cell(05,05,  converteValor($valorMinimoMensal) );
		
				$pdf->SetX(156);
				$pdf->Cell(05,05,  converteData($contratoColeta['inicio']) );
		
				$pdf->SetX(182);
				$pdf->Cell(05,05,  converteData($contratoColeta['vencimento']) );
				
				$pdf->ln(4);

		endforeach;
	 }else{
		$pdf->ln(4);
	}

	// FUNCAO DO EXTRATO IGUAL A CONSTRUÇÃO DO BOLETO

	$ordemTotal=0;
	$minimoTotal=0;
	$extraTotal=0;
	$valorMinimoTotal=0;
	$valorExtraTotal=0;
	$valorTotal=0;

	// ORDEM DE SERVIÇO GERADAS
	$leitura = read('contrato_ordem',"WHERE id AND id_contrato='$contratoId'  
					AND data>='$data1' AND data<='$data2' ORDER BY data ASC");
	if ($leitura) {
		foreach($leitura as $ordem):

			$statusColeta = $ordem['status'];
			$statusColeta = mostra('contrato_status',"WHERE id ='$statusColeta'");

			$tipoColeta1=$ordem['tipo_coleta1'];
			$tipoColeta = mostra('contrato_tipo_coleta',"WHERE id ='$tipoColeta1'");
		
			$quantidade = 0 ;
			$extra = 0;

			// SE REALIZADA A ORDEM DE SERVIÇO
			if($ordem['status']=='13'){ 

				$ordemTotal = $ordemTotal+1;

				$data = $ordem['data'];
				$quantidade =  $ordem['quantidade1'];

				// SE ENCONTROU EQUIPAMENTO PEGAR VALOR DA COLETA
				$leituraContratoColeta = read('contrato_coleta',"WHERE id AND inicio<='$data' AND vencimento>='$data' AND id_contrato='$contratoId' AND tipo_coleta='$tipoColeta1'");
				if ($leituraContratoColeta ) {
					foreach($leituraContratoColeta as $contratoColeta):
							$minimoDiario=$contratoColeta['quantidade'];
							$valorUnitario=$contratoColeta['valor_unitario'];
							$valorExtra=$contratoColeta['valor_extra'];
							$QuantidadeMinimoMensal = $contratoColeta['quantidade_mensal'];
					endforeach;

					$valorUnitario = $valorUnitario*$quantidade;
					$minimoTotal = $minimoTotal + $quantidade;
					$extra = 0;
					$valorExtra=0;

					//Minimo Diário e Minimo Mensal
					if($contrato['cobranca_coleta']=='2'){
						if($quantidade>$minimoDiario){ // CALCULAR EXTRA
							$extra = $quantidade-$minimoDiario;
							$valorExtra = $valorExtra*$extra;
							$valorUnitario = $valorUnitario*$minimoDiario;
							$quantidade = $minimoDiario;
							$minimoTotal = $minimoTotal + $minimoDiario;
							$extraTotal =  $extraTotal + $extra;
						}else{
							$valorUnitario = $valorUnitario*$quantidade;
							$minimoTotal = $minimoTotal + $quantidade;
							$extra = 0;
							$valorExtra=0;
						} // FIM TESTE DE COLETA EXTRA

					} // FIM //Minimo Diário e Minimo Mensal


					$quantidadeTotal=$quantidadeTotal+$quantidade+$extra;
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
	}

	$pdf->SetX(05);
	$pdf->Cell(10, 5, 'Total');
	$pdf->SetX(27);
	$pdf->Cell(10, 5, $ordemTotal,0,0,'R');
	$pdf->SetX(73);
	$pdf->Cell(10, 5, $minimoTotal,0,0,'R');
	$pdf->SetX(93);
	$pdf->Cell(10, 5, $extraTotal,0,0,'R');
	$pdf->SetX(115);
	$pdf->Cell(10, 5, converteValor($valorMinimoTotal),0,0,'R');
	$pdf->SetX(140);
	$pdf->Cell(10, 5, converteValor($valorExtraTotal),0,0,'R');
	$pdf->SetX(160);
	$pdf->Cell(10, 5, converteValor($valorTotal),0,0,'R');
	$pdf->ln(3);

	$pdf->SetX(05);
	$pdf->Cell(10, 5, 'Valor Total R$ :');
	$pdf->SetX(40);
	$pdf->Cell(10, 5, converteValor($valorTotal) );

	$pdf->SetX(62);
	$pdf->Cell(10, 5, 'Mínimo Mensal R$ :');
	$pdf->SetX(105);
	$pdf->Cell(10, 5, converteValor($valorMinimoMensal) );

	$pdf->SetX(130);
	$pdf->Cell(10, 5, 'Quantidade Mínimo Mensal :');
	$pdf->SetX(185);
	$pdf->Cell(10, 5,$QuantidadeMinimoMensal );
	$pdf->ln(4);

	if($contrato['cobrar_locacao']=='1'){
		$valorLocacao = $valorLocacao*$minimoDiario;
	}else{
		$valorLocacao=0;
	}

	$pdf->SetX(05);
	$pdf->Cell(10, 5, 'Locaçao/Manutençao R$ :');
	$pdf->SetX(55);
	$pdf->Cell(10, 5, converteValor($valorLocacao) );

	$valorManifesto=0;
	if($contrato['manifesto']=='1'){
		$valorManifesto = $contrato['manifesto_valor']*$ordemTotal;
	}

	$pdf->SetX(80);
	$pdf->Cell(10, 5, 'Manifesto R$ :');
	$pdf->SetX(110);
	$pdf->Cell(10, 5, converteValor($valorManifesto) );
	$pdf->ln(4);

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
	if($contrato['cobranca_coleta']=='4'){
		//$valorTotal = $valorMinimoMensal;
	}

	$valorExtrato = $valorTotal + $valorManifesto + $valorLocacao;

	$pdf->SetX(05);
	$pdf->Cell(10, 5, 'Base de Calculo R$ :');
	$pdf->SetX(50);
	$pdf->Cell(10, 5, converteValor($valorExtrato) );

	// CALCULAR ISS
	$valorIss=0;
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
	}

	$pdf->SetX(82);
	$pdf->Cell(10, 5, 'ISS R$ :');
	$pdf->SetX(105);
	$pdf->Cell(10, 5, converteValor($valorIss) );

	$valorExtrato = $valorExtrato + $valorIss ;

	$pdf->SetX(140);
	$pdf->Cell(10, 5, 'Total da NFe R$  :');
	$pdf->SetX(180);
	$pdf->Cell(10, 5, converteValor($valorExtrato) );
	$pdf->ln(4);

	$valorBoleto =0;
	if($contrato['boleto_bancario']=='1'){
		$valorBoleto = $contrato['boleto_valor'];
	}
	$pdf->SetX(05);
	$pdf->Cell(10, 5, 'Taxa Bancária R$ :');
	$pdf->SetX(40);
	$pdf->Cell(10, 5, converteValor($valorBoleto) );

	if($contrato['boleto_bancario']=='1'){
		$valorBoleto = $contrato['boleto_valor'];
	}

	$valorExtrato=$valorExtrato+$valorBoleto;
	$pdf->SetX(140);
	$pdf->Cell(10, 5, 'Valor  Boleto R$ :');
	$pdf->SetX(180);
	$pdf->Cell(10, 5, converteValor($valorExtrato) );
	$pdf->ln(2);

$i=$i+1;

endforeach;

ob_clean();  
$pdf->Output('relatorio.pdf', 'I');
 
?>