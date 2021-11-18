<?php

$data1 = $_SESSION['inicio'];
$data2 = $_SESSION['fim'];

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
	  $titulo="Etiqueta Saldo Negativa";
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

$total = conta('contrato',"WHERE id AND tipo='2' AND status='5' 
			AND saldo_etiqueta_minimo>saldo_etiqueta");
$leitura = read('contrato',"WHERE id AND tipo='2' AND status='5' 
			AND saldo_etiqueta_minimo>saldo_etiqueta ORDER BY saldo_etiqueta DESC");

$pdf=new RELATORIO();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','B',10);

$pdf->Ln(5);
$pdf->Line(0, 28, 220, 28); // insere linha divisória (Col, Lin, Col, Lin)
$pdf->SetX(4);
$pdf->Cell(20, 1, 'Id');
$pdf->SetX(15);
$pdf->Cell(20, 1, 'Nome');
$pdf->SetX(65);
$pdf->Cell(20, 1, 'Bairro');
$pdf->SetX(85);
$pdf->Cell(20, 1, 'Frequencia');
$pdf->SetX(108);
$pdf->Cell(20, 1, 'Dia da Semana');
$pdf->SetX(150);
$pdf->Cell(20, 1, 'Rota');
$pdf->SetX(160);
$pdf->Cell(20, 1, 'Saldo Atual');
$pdf->SetX(185);
$pdf->Cell(10, 1, 'Saldo Minimo');
$pdf->Line(0, 33, 220, 33); // insere linha divisória
$pdf->Ln(4);
$i=0;

foreach($leitura as $mostra):

	$pdf->SetFont('Arial','B',8);
	$pdf->ln();

	$pdf->SetX(4);
	$pdf->Cell(10, 5, $mostra['id']);

	$pdf->SetX(15);
	$clienteId = $mostra['id_cliente'];
	$cliente = mostra('cliente',"WHERE id ='$clienteId '");
	$pdf->Cell(10, 5, substr($cliente['nome'],0,25));

	$pdf->SetX(65);
	$pdf->Cell(10, 5, substr($cliente['bairro'],0,12));

	$diaSemana='';
	$rota='';
	if($mostra['domingo']==1){
		$diaSemana = ' Dom';
		$rota=$mostra['domingo_rota1'];
	}
	if($mostra['segunda']==1){
		$diaSemana = $diaSemana . ' Seg';
		$rota=$mostra[ 'segunda_rota1' ];
	}
	if($mostra['terca']==1){
		$diaSemana = $diaSemana . ' Ter';
		$rota=$mostra[ 'terca_rota1' ];
	}
	if($mostra['quarta']==1){
		$diaSemana = $diaSemana . ' Qua';
		$rota=$mostra[ 'quarta_rota1' ];
	}
	if($mostra['quinta']==1){
		$diaSemana = $diaSemana . ' Qui';
		$rota=$mostra[ 'quinta_rota1' ];
	}
	if($mostra['sexta']==1){
		$diaSemana = $diaSemana . ' Sex';
		$rota=$mostra[ 'sexta_rota1' ];
	}
	if($mostra['sabado']==1){
		$diaSemana = $diaSemana . ' Sab';
		$rota=$mostra[ 'sabado_rota1' ];
	}

	// 1 - semanal - 2 quinzenal - 3 mensal - 4 avulso
	$frequenciaId = $mostra[ 'frequencia' ];
	$frequencia = mostra( 'contrato_frequencia', "WHERE id AND id='$frequenciaId'" );
	$frequencia = $frequencia[ 'nome' ];

	$rota = mostra( 'contrato_rota', "WHERE id AND id='$rota'" );
	$rota = $rota[ 'nome' ];

	$pdf->SetX(85);
	$pdf->Cell(10, 5, $frequencia);
	$pdf->SetX(108);
	$pdf->Cell(10, 5, $diaSemana);
	$pdf->SetX(152);
	$pdf->Cell(10, 5, $rota);

	$pdf->SetX(170);
	$pdf->Cell(10, 5, $mostra['saldo_etiqueta']);

	$pdf->SetX(195);
	$pdf->Cell(10, 5, $mostra['saldo_etiqueta_minimo']);


	$i=$i+1;
	if ($i>45){
		$pdf->AddPage();   
		$pdf->SetFont('Arial','B',10);
		$pdf->Ln(5);
		$pdf->Line(0, 28, 220, 28); // insere linha divisória (Col, Lin, Col, Lin)
		$pdf->SetX(4);
		$pdf->Cell(20, 1, 'Id');
		$pdf->SetX(15);
		$pdf->Cell(20, 1, 'Nome');
		$pdf->SetX(65);
		$pdf->Cell(20, 1, 'Bairro');
		$pdf->SetX(85);
		$pdf->Cell(20, 1, 'Frequencia');
		$pdf->SetX(110);
		$pdf->Cell(20, 1, 'Dia da Semana');
		$pdf->SetX(150);
		$pdf->Cell(20, 1, 'Rota');
		$pdf->SetX(160);
		$pdf->Cell(20, 1, 'Saldo Atual');
		$pdf->SetX(185);
		$pdf->Cell(10, 1, 'Saldo Minimo');
		$pdf->Line(0, 33, 220, 33); // insere linha divisória
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