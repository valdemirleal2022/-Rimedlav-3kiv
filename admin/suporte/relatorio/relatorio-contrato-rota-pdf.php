<?php

$dataRota=$_SESSION[ 'dataRota' ];
$rotaId = $_SESSION[ 'rotaRoteiro' ];

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
	  $titulo="Contrato por Rota";
      $this->Ln(5);
	  $this->Cell(0,5,$titulo,0,0,'C');
      $this->Ln(10);
    }
    function Footer(){
      $this->SetY(-15);
      $this->SetFont('Arial','I',9);
      $this->Cell(0,10,'Página '.$this->PageNo().'/{nb}',0,0,'C');
    }
}

	$dia_semana = diaSemana($dataRota);
	$numero_semana = numeroSemana($dataRota);
	
	//DOMINGO

	if ( $numero_semana == 0 ) {
		$leitura = read( 'contrato', "WHERE id AND status='5' AND domingo='1' 
										AND domingo_rota1='$rotaId' OR domingo_rota2='$rotaId' 
										ORDER BY domingo_hora1 ASC");
		$total = conta( 'contrato', "WHERE id AND status='5' AND domingo='1' 
										AND domingo_rota1='$rotaId' OR domingo_rota2='$rotaId' ");
	}

	if ( $numero_semana == 1 ) {
		$leitura = read( 'contrato', "WHERE id AND status='5' AND segunda='1' 
										AND segunda_rota1='$rotaId' OR segunda_rota2='$rotaId' 
										ORDER BY segunda_hora1 ASC");
		$total = conta( 'contrato', "WHERE id AND status='5' AND segunda='1' 
										AND segunda_rota1='$rotaId' OR segunda_rota2='$rotaId'");
	}

	// TERCA
	if ( $numero_semana == 2 ) {
		$leitura = read( 'contrato', "WHERE id AND status='5' AND terca='1' 
										AND terca_rota1='$rotaId' OR terca_rota2='$rotaId' 
											ORDER BY terca_hora1 ASC");
		$total = conta( 'contrato', "WHERE id AND status='5' AND terca='1' 
											AND terca_rota1='$rotaId' OR terca_rota2='$rotaId'");
	}

	//QUARTA
	if ( $numero_semana == 3 ) {
		$leitura = read( 'contrato', "WHERE id AND status='5' AND quarta='1' 
										AND quarta_rota1='$rotaId' OR quarta_rota2='$rotaId' 
										ORDER BY quarta_hora1 ASC");
		$total = conta( 'contrato', "WHERE id AND status='5' AND  quarta='1' 
										AND  quarta_rota1='$rotaId' OR quarta_rota2='$rotaId'");
	}
	
	//QUINTA
	if ( $numero_semana == 4 ) {

		$leitura = read( 'contrato', "WHERE id AND status='5' AND quinta='1' 
										AND quinta_rota1='$rotaId' OR quinta_rota2='$rotaId' 
										ORDER BY quinta_hora2, quinta_hora1 ASC");
		
		$total = conta( 'contrato', "WHERE id AND status='5' AND  quinta='1' 
										AND  quinta_rota1='$rotaId' OR quinta_rota2='$rotaId'");
	}
	
	//SEXTA
	if ( $numero_semana == 5 ) {
		$leitura = read( 'contrato', "WHERE id AND status='5' AND sexta='1' 
										AND sexta_rota1='$rotaId' OR sexta_rota2='$rotaId'
										ORDER BY sexta_hora1 ASC");
		$total = conta( 'contrato', "WHERE id AND status='5' AND  sexta='1' 
										AND  sexta_rota1='$rotaId' OR sexta_rota2='$rotaId'");
	}
	
	//SABADO
	if ( $numero_semana == 6 ) {
		$leitura = read( 'contrato', "WHERE id AND status='5' AND sabado='1' 
										AND sabado_rota1='$rotaId' OR sabado_rota2='$rotaId' 
										ORDER BY sabado_hora1 ASC");
		$total = conta( 'contrato', "WHERE id AND status='5' AND  sabado='1' 
										AND  sabado_rota1='$rotaId' OR sabado_rota2='$rotaId' ");
	}

$rotaMostra = mostra('contrato_rota',"WHERE id ='$rotaId'");

$pdf=new RELATORIO("L","mm","A4");
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','B',10);
$pdf->Cell(22, 1, 'Data :');
$pdf->Cell(22, 1, converteData($dataRota));
$pdf->Cell(22, 1, 'Rota :');
$pdf->Cell(22, 1, $rotaMostra['nome']);

$pdf->Ln(5);
$pdf->Line(10, 28, 290, 28); // insere linha divisória (Col, Lin, Col, Lin)

$pdf->SetX(10);
$pdf->Cell(20, 1, 'Hora');
$pdf->SetX(20);
$pdf->Cell(10, 1, 'Rota');
$pdf->SetX(30);
$pdf->Cell(20, 1, 'Cliente');
$pdf->SetX(80);
$pdf->Cell(20, 1, 'Bairro');
$pdf->SetX(110);
$pdf->Cell(20, 1, 'Endereco');
$pdf->SetX(200);
$pdf->Cell(20, 1, 'Coleta');
$pdf->SetX(240);
$pdf->Cell(10, 1, 'Frequencia');
$pdf->SetX(265);
$pdf->Cell(20, 1, 'Contrato');

$pdf->Line(10, 33, 290, 33); // insere linha divisória
$pdf->Ln(4);
$i=0;

foreach($leitura as $mostra):

	$pdf->SetFont('Arial','B',8);
	$pdf->ln();

	if ( $numero_semana == 0 ) {
					$hora = $mostra['domingo_hora1'];
					$rota = $mostra['domingo_rota1'];
				}
				if ( $numero_semana == 1 ) {
					$hora = $mostra['segunda_hora1'];
					$rota = $mostra['segunda_rota1'];
				}
		
				if ( $numero_semana == 2 ) {
					$hora = $mostra['terca_hora1'];
					$rota = $mostra['terca_rota1'];
				}
		
				if ( $numero_semana == 3 ) {
					$hora = $mostra['quarta_hora1'];
					$rota = $mostra['quarta_rota1'];
				}
		
				if ( $numero_semana == 4 ) { //QUINTA
					$hora = $mostra['quinta_hora1'];
					$rota = $mostra['quinta_rota1'];
					
					if($mostra['quinta_rota2']==$rotaId){
						$hora = $mostra['quinta_hora2'];
						$rota = $mostra['quinta_rota2'];
					}
				}
				if ($numero_semana == 5 ) {
					$hora = $mostra['sexta_hora1'];
					$rota = $mostra['sexta_rota1'];
					
					if($mostra['sexta_rota2']==$rotaId){
						$hora = $mostra['sexta_hora2'];
						$rota = $mostra['sexta_rota2'];
					}

				}
				if ( $numero_semana == 6 ) {
					$hora = $mostra['sabado_hora1'];
					$rota = $mostra['sabado_rota1'];
				}

	$pdf->SetX(10);
	$pdf->Cell(10, 5, $hora);

	$pdf->SetX(20);
	$pdf->Cell(10, 5, $rotaMostra['nome']);
	
	$clienteId = $mostra['id_cliente'];
	$cliente = mostra('cliente',"WHERE id ='$clienteId'");

	$pdf->SetX(30);
	$pdf->Cell(10, 5, substr($cliente['nome'],0,25));

	$pdf->SetX(80);
	$pdf->Cell(10, 5, substr($cliente['bairro'],0,20));
	
	$endereco = tirarAcentos($cliente['endereco'].', '.$cliente['numero'].' '.$cliente['complemento']);

	$pdf->SetX(110);
	$pdf->Cell(10, 5, $endereco);
	
	$contratoId = $mostra['id'];
	$contratoColeta = mostra('contrato_coleta',"WHERE id_contrato  ='$contratoId'");
		
	$tipoColetaId = $contratoColeta['tipo_coleta'];
	$tipoColeta = mostra('contrato_tipo_coleta',"WHERE id ='$tipoColetaId'");
		
	$pdf->SetX(200);
	$pdf->Cell(10, 5, $tipoColeta['nome']);

	$frequenciaId = $mostra['frequencia'];
	$frequencia= mostra('contrato_frequencia',"WHERE id ='$frequenciaId'");
	
	$pdf->SetX(240);
	$pdf->Cell(10, 5, $frequencia['nome']);

	$pdf->SetX(265);
	$pdf->Cell(10, 5, $mostra['id'].'|'.substr($mostra['controle'],0,6));

	$i=$i+1;

	if ($i>28){
		$pdf->AddPage();   
		$pdf->SetFont('Arial','B',10);
		$pdf->Ln(5);

		$pdf->Line(10, 28, 290, 28); // insere linha divisória (Col, Lin, Col, Lin)

		$pdf->SetX(10);
		$pdf->Cell(20, 1, 'Hora');
		$pdf->SetX(20);
		$pdf->Cell(10, 1, 'Rota');
		$pdf->SetX(30);
		$pdf->Cell(20, 1, 'Cliente');
		$pdf->SetX(80);
		$pdf->Cell(20, 1, 'Bairro');
		$pdf->SetX(110);
		$pdf->Cell(20, 1, 'Endereco');
		$pdf->SetX(200);
		$pdf->Cell(20, 1, 'Coleta');
		$pdf->SetX(240);
		$pdf->Cell(10, 1, 'Frequencia');
		$pdf->SetX(265);
		$pdf->Cell(20, 1, 'Contrato');

		$pdf->Line(10, 33, 290, 33); // insere linha divisória
		
		$pdf->Ln(4);
		$i=0;
	}
endforeach;

$pdf->SetFont('Arial','B',8);
$pdf->ln(10);
$pdf->Cell(10, 5, 'Total de registros : '. $total);
	

ob_clean();  
$pdf->Output('relatorio.pdf', 'I');
 
?>