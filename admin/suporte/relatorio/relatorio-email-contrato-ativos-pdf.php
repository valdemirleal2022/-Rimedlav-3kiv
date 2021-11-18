<?php

$contratoTipo = $_SESSION['contratoTipo'];

$total = conta('contrato',"WHERE id AND tipo='2' AND status='5'");
$leitura = read('contrato',"WHERE id AND tipo='2' AND status='5' ORDER BY inicio ASC");

if (!empty($contratoTipo)) {
	$total = conta('contrato',"WHERE id AND tipo='2' AND contrato_tipo='$contratoTipo' AND status='5'");
	$leitura = read('contrato',"WHERE id AND tipo='2' AND contrato_tipo='$contratoTipo' AND status='5' ORDER BY inicio ASC");
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
	  $titulo="Contratos Email Ativos";
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

$leitura = read('contrato',"WHERE id AND tipo='2' AND contrato_tipo='$contratoTipo' AND status='5' ORDER BY inicio ASC");

$contratoTipo = mostra('contrato_tipo',"WHERE id ='$contratoTipo'");

$pdf=new RELATORIO();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','B',10);

$pdf->SetX(10);
$pdf->Cell(22, 1, 'Tipo de Contrato :');
$pdf->SetX(42);
$pdf->Cell(22, 1, $contratoTipo['nome']);

$pdf->Ln(5);
$pdf->Line(0, 28, 220, 28); // insere linha divisória (Col, Lin, Col, Lin)
$pdf->SetX(4);
$pdf->Cell(20, 1, 'Id');
$pdf->SetX(15);
$pdf->Cell(20, 1, 'Nome');
$pdf->SetX(80);
$pdf->Cell(20, 1, 'Email');
$pdf->SetX(150);
$pdf->Cell(20, 1, 'Email - Financeiro');
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

	$pdf->SetX(80);
	$pdf->Cell(10, 5, $cliente['email']);

	$pdf->SetX(150);
	$pdf->Cell(10, 5, $cliente['email_financeiro']);
	
	$i=$i+1;
	if ($i>45){
		$pdf->AddPage();   
		$pdf->SetFont('Arial','B',10);
		$pdf->Ln(5);
		$pdf->Line(0, 28, 220, 28); // insere linha divisória (Col, Lin, Col, Lin)
		$pdf->Ln(5);
		$pdf->Line(0, 28, 220, 28); // insere linha divisória (Col, Lin, Col, Lin)
		$pdf->SetX(4);
		$pdf->Cell(20, 1, 'Id');
		$pdf->SetX(15);
		$pdf->Cell(20, 1, 'Nome');
		$pdf->SetX(80);
		$pdf->Cell(20, 1, 'Email');
		$pdf->SetX(150);
		$pdf->Cell(20, 1, 'Email - Financeiro');
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