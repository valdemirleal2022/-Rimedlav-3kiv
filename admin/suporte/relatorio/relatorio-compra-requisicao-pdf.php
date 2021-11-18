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
	  $titulo="Compra - Requisições";
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

$total = conta('estoque_material_requisicao',"WHERE id AND data>='$data1' 
											  AND data<='$data2'");
$leitura = read('estoque_material_requisicao',"WHERE id AND data>='$data1' 
											  AND data<='$data2' ORDER BY data ASC");


$pdf=new RELATORIO("L","mm","A4");
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','B',10);
$pdf->Cell(22, 1, 'Data Inicio :');
$pdf->Cell(22, 1, converteData($data1));
$pdf->Cell(22, 1, 'Data Fim :');
$pdf->Cell(22, 1, converteData($data2));
 
$pdf->Ln(5);
$pdf->Line(5, 28, 290, 28); // insere linha divisória (Col, Lin, Col, Lin)
$pdf->SetX(10);
$pdf->Cell(20, 1, 'Id');
$pdf->SetX(20);
$pdf->Cell(20, 1, 'Area');
$pdf->SetX(50);
$pdf->Cell(20, 1, 'Solicitante');
$pdf->SetX(80);
$pdf->Cell(20, 1, 'Data');

$pdf->SetX(100);
$pdf->Cell(20, 1, 'Material');

$pdf->SetX(195);
$pdf->Cell(20, 1, 'Q Solicitada');

$pdf->SetX(220);
$pdf->Cell(20, 1, 'Q Liberada');

$pdf->SetX(240);
$pdf->Cell(20, 1, 'Estoque');

$pdf->SetX(270);
$pdf->Cell(20, 1, 'Status');
 
$pdf->Line(5, 33, 290, 33); // insere linha divisória
$pdf->Ln(4);
$i=0;

foreach($leitura as $mostra):

	$pdf->SetFont('Arial','B',8);
	$pdf->ln();

	 
	$pdf->SetX(10);
	$pdf->Cell(10, 5, $mostra['id']);
 
	$pdf->SetX(20);
	$pdf->Cell(10, 5, $mostra['area']);

	$solicitanteId = $mostra['solicitante'];
	$solicitante = mostra('usuarios',"WHERE id ='$solicitanteId'");
	$pdf->SetX(50);
	$pdf->Cell(10, 5, $solicitante['nome']);

	$pdf->SetX(80);
	$pdf->Cell(10, 5, converteData($mostra['data']));
	
	$materialId = $mostra['id_material'];
	$estoque = mostra('estoque_material',"WHERE id ='$materialId'");
	$pdf->SetX(100);
	$pdf->Cell(10, 5,$estoque['nome']);

	$pdf->SetX(210);
	$pdf->Cell(10, 5, $mostra['quantidade']);

	$pdf->SetX(230);
	$pdf->Cell(10, 5, $mostra['quantidade_liberada']);

	$pdf->SetX(245);
	$pdf->Cell(10, 5, $estoque['estoque']);

	$pdf->SetX(270);
	$pdf->Cell(10, 5, $mostra['status']);
 
	$i=$i+1;
	if ($i>28){
		$pdf->AddPage();   
		$pdf->SetFont('Arial','B',10);
		$pdf->Ln(5);
		$pdf->Line(5, 28, 290, 28); // insere linha divisória (Col, Lin, Col, Lin)
		$pdf->SetX(10);
		$pdf->Cell(20, 1, 'Id');
		$pdf->SetX(20);
		$pdf->Cell(20, 1, 'Area');
		$pdf->SetX(50);
		$pdf->Cell(20, 1, 'Solicitante');
		$pdf->SetX(80);
		$pdf->Cell(20, 1, 'Data');

		$pdf->SetX(100);
		$pdf->Cell(20, 1, 'Material');

		$pdf->SetX(195);
		$pdf->Cell(20, 1, 'Q Solicitada');

		$pdf->SetX(220);
		$pdf->Cell(20, 1, 'Q Liberada');

		$pdf->SetX(240);
		$pdf->Cell(20, 1, 'Estoque');

		$pdf->SetX(270);
		$pdf->Cell(20, 1, 'Status');
		$pdf->Line(5, 28, 290, 28); // insere linha divisória (Col, Lin, Col, Lin)
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