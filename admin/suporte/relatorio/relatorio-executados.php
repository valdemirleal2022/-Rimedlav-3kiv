<?php
require_once("js/fpdf/fpdf.php");
define("FPDF_FONTPATH","font/");

if(!empty($_POST['data1'])){
		$data1 = $_POST['data1'];
	}
	if(!empty($_POST['data2'])){
		$data2 = $_POST['data2'];
}

class RELATORIO extends FPDF{
    function Header(){
	  $titulo=SITENOME;
      $this->SetFont('Arial','I',8);
      $this->Cell(0,5,$titulo,0,0,'L');
      $titulo=date('d/m/Y H:i:s');
      $this->SetFont('Arial','I',8);
      $this->Cell(0,5,$titulo,0,0,'R'); 	  
	  $this->SetFont('Arial','B',12);
	  $titulo="Serviços Executados";
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

$leitura = read('contrato_ordem',"WHERE id AND status='12' ORDER BY data DESC, hora ASC");

$pdf=new RELATORIO();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','B',10);
$pdf->Ln(1);
$pdf->Line(0, 25, 220, 25); // insere linha divisória (Col, Lin, Col, Lin)
$pdf->Cell(20, 1, 'Id');
$pdf->SetX(25);
$pdf->Cell(20, 1, 'Nome');
$pdf->SetX(80);
$pdf->Cell(20, 1, 'Data');
$pdf->SetX(110);
$pdf->Cell(20, 1, 'valor');
$pdf->SetX(130);
$pdf->Cell(20, 1, 'status');
$pdf->SetX(160);
$pdf->Cell(20, 1, 'Banco');
$pdf->SetX(180);
$pdf->Cell(10, 1, 'Forma Pag');
$pdf->Line(0, 33, 220, 33); // insere linha divisória
$pdf->Ln(4);~
$i=0;

foreach($leitura as $mostra):

	$pdf->SetFont('Arial','B',8);
	$pdf->ln();
	$pdf->Cell(10, 5, $mostra['id']);
	$pdf->SetX(25);
	$contratoId = $mostra['id_contrato'];
	$clienteId = $mostra['id_cliente'];
	$cliente = mostra('cliente',"WHERE id ='$clienteId '");
	$pdf->Cell(10, 5, substr($cliente['nome'],0,20));
	$pdf->SetX(80);
	$pdf->Cell(10, 5, converteData($mostra['data']));
	$pdf->SetX(110);
	$pdf->Cell(10, 5, $mostra['hora']);
	$pdf->SetX(130);
	$pdf->Cell(10, 5, $mostra['tipo_coleta1']);
	$pdf->SetX(180);
	$pdf->Cell(10, 5, $mostra['hora']);

endforeach;

ob_clean();  
$pdf->Output('relatorio.pdf', 'I');
 
?>