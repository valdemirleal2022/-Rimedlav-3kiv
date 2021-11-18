<?php

$data1 = $_SESSION['inicio'];
$data2 = $_SESSION['fim'];

if(isset($_SESSION[ 'rotaColeta' ])){
	$rotaId =$_SESSION[ 'rotaColeta' ];
}


require_once("js/fpdf/fpdf.php");
define("FPDF_FONTPATH","font/");


class RELATORIO extends FPDF{
    function Header(){
	  $titulo=SITENOME;
      $this->SetFont('Arial','I',8);
      $this->Cell(0,5,$titulo,0,0,'L');
      $titulo=date('d/m/Y H:i:s');
      $this->SetFont('Arial','I',8);
      $this->Cell(0,5,$titulo,0,0,'R'); 	  
	  $this->SetFont('Arial','B',12);
	  $titulo="Rentabilidade";
      $this->Ln(3);
	  $this->Cell(0,5,$titulo,0,0,'C');
      $this->Ln(10);
    }
    function Footer(){
      $this->SetY(-15);
      $this->SetFont('Arial','I',9);
      $this->Cell(0,10,'Pagina '.$this->PageNo().'/{nb}',0,0,'C');
    }
}


$total = conta('contrato_ordem',"WHERE data>='$data1' AND data<='$data2' 
														AND status='13'");
$leitura = read('contrato_ordem',"WHERE data>='$data1' AND data<='$data2' 
													AND status='13' ORDER BY data ASC, rota ASC, hora ASC");

if(!empty($rotaId)){
	$rotaColeta = mostra('contrato_rota',"WHERE id ='$rotaId'");
	$total = conta('contrato_ordem',"WHERE data>='$data1' AND data<='$data2' AND rota='$rotaId' 
														AND status='13'");
	$leitura = read('contrato_ordem',"WHERE data>='$data1' AND data<='$data2' AND rota='$rotaId' 
														AND status='13'  ORDER BY data ASC, rota ASC, hora ASC");
}


$pdf=new RELATORIO("L","mm","A4");
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','B',10);
$pdf->Cell(22, 1, 'Data Inicio :');
$pdf->Cell(22, 1, converteData($data1));
$pdf->Cell(22, 1, 'Data Fim :');
$pdf->Cell(22, 1, converteData($data2));
$pdf->Cell(22, 1, 'Rota :');
$pdf->Cell(22, 1, $rotaColeta['nome']);

$pdf->Ln(5);
$pdf->Line(10, 26, 290, 26); // insere linha divisória (Col, Lin, Col, Lin)

$pdf->SetX(10);
$pdf->Cell(20, 1, 'Hora');

$pdf->SetX(22);
$pdf->Cell(20, 1, 'Coleta');

$pdf->SetX(40);
$pdf->Cell(20, 1, 'Nome');

$pdf->SetX(100);
$pdf->Cell(20, 1, 'Bairro');

$pdf->SetX(135);
$pdf->Cell(20, 1, 'Numero');

$pdf->SetX(155);
$pdf->Cell(20, 1, 'Coleta');

$pdf->SetX(187);
$pdf->Cell(10, 1, 'Previsto');

$pdf->SetX(204);
$pdf->Cell(10, 1, 'Coletado');

$pdf->SetX(223);
$pdf->Cell(10, 1, 'Vl Unit');

$pdf->SetX(240);
$pdf->Cell(10, 1, 'Faturado');

$pdf->SetX(263);
$pdf->Cell(10, 1, 'Data');

$pdf->Line(10, 31, 290, 31); // insere linha divisória
$pdf->Ln(4);
$i=0;

$valorTotal=0;

foreach($leitura as $mostra):

	$pdf->SetFont('Arial','B',8);
	$pdf->ln();

	$pdf->SetX(10);
	$pdf->Cell(10, 5, $mostra['hora']);

	$pdf->SetX(22);
	$pdf->Cell(10, 5, $mostra['hora_coleta']);
	
	$clienteId = $mostra['id_cliente'];
	$cliente = mostra('cliente',"WHERE id ='$clienteId'");

	$pdf->SetX(40);
	$pdf->Cell(10, 5, substr($cliente['nome'],0,30));

	$pdf->SetX(100);
	$pdf->Cell(10, 5, substr($cliente['bairro'],0,20));

	$pdf->SetX(135);
	$pdf->Cell(10, 5, $mostra['id']);

	$tipoColetaId = $mostra['tipo_coleta1'];
    $coleta = mostra('contrato_tipo_coleta',"WHERE id ='$tipoColetaId'");
	$pdf->SetX(155);
	$pdf->Cell(10, 5, $coleta['nome']);

	$contratoId = $mostra['id_contrato'];
    $dataroteiro=$mostra['data'];
	$contratoColeta = mostra( 'contrato_coleta', "WHERE id AND inicio<='$dataroteiro' AND vencimento>='$dataroteiro' AND id_contrato='$contratoId'  AND tipo_coleta='$tipoColetaId'" );

	$pdf->SetX(195);
	$pdf->Cell(10, 5, $contratoColeta['quantidade']);

	$pdf->SetX(210);
	$pdf->Cell(10, 5, $mostra['quantidade1']);
	
	$pdf->SetX(227);
	$pdf->Cell(10, 5, converteValor($contratoColeta['valor_unitario']),0,0,'R');
	
	$valor=$contratoId = $mostra['quantidade1']*$contratoColeta['valor_unitario'];

	if($mostra['quantidade1']==0){
			$valor = $contratoColeta['quantidade'] * $contratoColeta['valor_unitario'];
		}

	$pdf->SetX(248);
	$pdf->Cell(10, 5, converteValor($valor),0,0,'R');

	$contrato = mostra( 'contrato', "WHERE id AND id='$contratoId'" );
	$pdf->SetX(263);
	$pdf->Cell(10, 5, converteData($mostra['data']));

	
	$rotaId =$mostra[ 'rota' ];
	$rotaColeta = mostra('contrato_rota',"WHERE id ='$rotaId'");

	$pdf->SetX(280);
	$pdf->Cell(10, 5, $rotaColeta['nome']);

	$valorTotal=$valorTotal+$valor;

	$i=$i+1;
	if ($i>30){
		$pdf->AddPage();   
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(22, 1, 'Data Inicio :');
		$pdf->Cell(22, 1, converteData($data1));
		$pdf->Cell(22, 1, 'Data Fim :');
		$pdf->Cell(22, 1, converteData($data2));
		$pdf->Cell(22, 1, 'Rota :');
		$pdf->Cell(22, 1, $rotaColeta['nome']);

		$pdf->Ln(5);
		$pdf->Line(10, 26, 290, 26); // insere linha divisória (Col, Lin, Col, Lin)

		$pdf->SetX(10);
		$pdf->Cell(20, 1, 'Hora');

		$pdf->SetX(22);
		$pdf->Cell(20, 1, 'Coleta');

		$pdf->SetX(40);
		$pdf->Cell(20, 1, 'Nome');

		$pdf->SetX(100);
		$pdf->Cell(20, 1, 'Bairro');

		$pdf->SetX(135);
		$pdf->Cell(20, 1, 'Numero');

		$pdf->SetX(155);
		$pdf->Cell(20, 1, 'Coleta');

		$pdf->SetX(187);
		$pdf->Cell(10, 1, 'Previsto');

		$pdf->SetX(204);
		$pdf->Cell(10, 1, 'Coletado');

		$pdf->SetX(223);
		$pdf->Cell(10, 1, 'Vl Unit');

		$pdf->SetX(240);
		$pdf->Cell(10, 1, 'Faturado');
		
		$pdf->SetX(262);
		$pdf->Cell(10, 1, 'Controle');

		$pdf->Line(10, 31, 290, 31); // insere linha divisória
		$pdf->Ln(4);

		$i=0;
	}
endforeach;

$pdf->Ln(4);
$pdf->SetX(248);
$pdf->Cell(10, 5, converteValor($valorTotal),0,0,'R');
$pdf->SetFont('Arial','B',8);
$pdf->ln(4);
$pdf->Cell(10, 5, 'Total de registros : '. $total);
$pdf->ln(4);

if ($i>25){
	$pdf->AddPage();   
}


$pdf->SetX(125);
$pdf->Cell(10, 5, 'Coleta');
			
$pdf->SetX(165);
$pdf->Cell(10, 5, 'Previsto');
			
$pdf->SetX(185);
$pdf->Cell(10, 5, 'Valor Previsto');
			
$pdf->SetX(210);
$pdf->Cell(10, 5, 'Coletado');
			
$pdf->SetX(230);
$pdf->Cell(10, 5, 'Valor Coletado');
$pdf->Ln(5);


$valorTotal=0;
$previstoTotal=0;
$pesagemPrevista=0;
$pesagemColetada=0;

$leituraTipoColeta= read('contrato_tipo_coleta',"WHERE id ORDER BY id ASC");

if($leituraTipoColeta){
	foreach($leituraTipoColeta as $tipoColeta):
	
		$nome= $tipoColeta['nome'];
		$tipoColetaId = $tipoColeta['id'];
		$pesoMedio = $tipoColeta['peso_medio'];
	
		$previsto=0;
		$coletado=0;
		$valorColeta=0;
		$valorPrevisto=0;
		 
		foreach($leitura as $mostra):
	
			if($mostra['tipo_coleta1']==$tipoColetaId ){
				
				$contratoId = $mostra['id_contrato'];
				
				$dataroteiro=$mostra['data'];
				$contratoColeta = mostra( 'contrato_coleta', "WHERE id AND inicio<='$dataroteiro' AND vencimento>='$dataroteiro' AND id_contrato='$contratoId' 
										   AND tipo_coleta='$tipoColetaId'" );
				
				$coletado=$coletado+$mostra['quantidade1'];
				
				if($contratoColeta){
					
					$previsto=$previsto+$contratoColeta['quantidade'];
					
					$valorPrevisto=$valorPrevisto+($contratoColeta['quantidade']*$contratoColeta['valor_unitario']);
										
					$valor=$mostra['quantidade1']*$contratoColeta['valor_unitario'];
					
					if($mostra['quantidade1']==0){
						$valor=$contratoColeta['quantidade']*$contratoColeta['valor_unitario'];
					}
					$valorColeta=$valorColeta+$valor;
				}
			}

		endforeach;
	
		if($valorColeta<>'0'){
			
			$pdf->SetX(125);
			$pdf->Cell(10, 5, $nome);
			
			$pdf->SetX(165);
			$pdf->Cell(10, 5, $previsto,0,0,'R');
			
			$pdf->SetX(190);
			$pdf->Cell(10, 5, converteValor($valorPrevisto),0,0,'R');
			
			$pdf->SetX(210);
			$pdf->Cell(10, 5, $coletado,0,0,'R');
			
			$pdf->SetX(240);
			$pdf->Cell(10, 5, converteValor($valorColeta),0,0,'R');
			$pdf->Ln(5);
			
			$previstoTotal=$previstoTotal+$valorPrevisto;
			
			$valorTotal=$valorTotal+$valorColeta;
			
			$pesagemPrevista=$pesagemPrevista+($pesoMedio*$previsto);
			
			$pesagemColetada=$pesagemColetada+($pesoMedio*$coletado);
			
		}
	
	endforeach;
		
		$pdf->SetX(190);
		$pdf->Cell(10, 5, converteValor($previstoTotal),0,0,'R');
		
		$pdf->SetX(240);
		$pdf->Cell(10, 5, converteValor($valorTotal),0,0,'R');
	
		$pdf->Ln(4);
 }

$pdf->Ln(6);
$pdf->SetX(40);
$pdf->Cell(10, 5, 'Veiculo/Aterro');
$pdf->Ln(5);

$kmTotal=0;
$pesagemTotal=0;

$leituraVeiculo= read('veiculo_liberacao',"WHERE saida>='$data1' AND saida<='$data2' AND rota ='$rotaId'");
if($leituraVeiculo){
	foreach($leituraVeiculo as $veiculoLiberacao):
		
		if($veiculoLiberacao['km_chegada']>0){
			$km= $veiculoLiberacao['km_chegada'] - $veiculoLiberacao['km_saida'];
		}else{
			$km= 0;
		}
		
		$kmTotal=$kmTotal+$km;
	
		$pesagem = $veiculoLiberacao['pesagem'] ; 
		$pesagemTotal=$pesagemTotal+$pesagem;
	
	endforeach;
}
	$veiculoId=$veiculoLiberacao['id_veiculo'];
	$veiculo = mostra('veiculo',"WHERE id ='$veiculoId'");
	$pdf->SetX(40);
	$pdf->Cell(10, 5, 'Modelo : '.$veiculo['modelo']. ' Placa : '.$veiculo['placa']);
	$pdf->Ln(5);
	$aterroId=$veiculoLiberacao['aterro'];
	$aterro = mostra('aterro',"WHERE id ='$aterroId'");
	$pdf->SetX(40);
	$pdf->Cell(10, 5,'Aterro : '. $aterro['nome']);
	$pdf->Ln(5);
	$pdf->SetX(40);
	$pdf->Cell(10, 5, 'Km : '. $kmTotal );
	$pdf->Ln(5);
	$pdf->SetX(40);
	$pdf->Cell(10, 5, 'Pesagem : ' .$pesagemTotal . ' - Previsto : '. $pesagemPrevista . ' = '.($pesagemTotal - $pesagemPrevista) );
	$pdf->Ln(5);

	$pdf->SetX(40);
	$pdf->Cell(10, 5, 'Pesagem : ' .$pesagemTotal . ' - Coletado : '. $pesagemColetada . ' = '.($pesagemTotal - $pesagemColetada) );
	$pdf->Ln(5);
 

ob_clean();  
$pdf->Output('relatorio.pdf', 'I');
 
?>