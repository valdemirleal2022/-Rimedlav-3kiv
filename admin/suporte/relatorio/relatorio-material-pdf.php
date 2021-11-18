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
	  $titulo="Retirada de Material";
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


$total = conta('estoque_material',"WHERE id");
$leitura = read('estoque_material',"WHERE id ORDER BY codigo ASC");


$pdf=new RELATORIO("L","mm","A4");
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','B',10);
 

$pdf->Ln(5);

$pdf->Line(5, 28, 290, 28); // insere linha divisória (Col, Lin, Col, Lin)

$pdf->SetX(5);
$pdf->Cell(20, 1, 'Codigo');

$pdf->SetX(25);
$pdf->Cell(20, 1, 'Descrição');
	
$pdf->SetX(145);
$pdf->Cell(20, 1, 'Estoque');

$pdf->SetX(165);
$pdf->Cell(20, 1, 'Estoque Min');

$pdf->SetX(190);
$pdf->Cell(20, 1, 'Unidade');

$pdf->SetX(210);
$pdf->Cell(20, 1, 'Valor Unitario');

$pdf->SetX(240);
$pdf->Cell(20, 1, 'Valor Total');

$pdf->SetX(270);
$pdf->Cell(20, 1, 'Status');

$pdf->Line(5, 33, 290, 33); // insere linha divisória
$pdf->Ln(4);
$i=0;

foreach($leitura as $mostra):

	$pdf->SetFont('Arial','B',8);
	$pdf->ln();

	 	$pdf->SetX(05);
	$pdf->Cell(10, 5,$mostra['codigo']);

	$pdf->SetX(25);
	$pdf->Cell(10, 5,$mostra['nome']);

	$pdf->SetX(145);
	$pdf->Cell(10, 5, $mostra['estoque'],0,0,'R');

	$pdf->SetX(165);
	$pdf->Cell(10, 5, $mostra['estoque_minimo'],0,0,'R');

	$pdf->SetX(190);
	$pdf->Cell(10, 5,$mostra['unidade']);

	$valor = $mostra['estoque']*$mostra['valor_unitario'];

	$pdf->SetX(215);
	$pdf->Cell(10, 5, converteValor($mostra['valor_unitario']),0,0,'R');

	$pdf->SetX(240);
	$pdf->Cell(10, 5, converteValor($valor),0,0,'R');

	if($mostra['status']==1){
         $pdf->SetX(270);
		 $pdf->Cell(10, 5, 'Ativo');
      }else{
         $pdf->SetX(270);
		 $pdf->Cell(10, 5, 'Inativo');
     }

	//$tipoId = $mostra['id_tipo'];
//	$estoqueMaterial = mostra('estoque_material_tipo',"WHERE id ='$tipoId '");
//	 
//	$pdf->SetX(5);
//	$pdf->Cell(10, 5, $estoqueMaterial['nome']);

	$i=$i+1;
	if ($i>28){
		$pdf->AddPage();   
		$pdf->SetFont('Arial','B',10);
		$pdf->Ln(5);
		$pdf->Line(5, 28, 290, 28); // insere linha divisória (Col, Lin, Col, Lin)
		
		$pdf->SetX(5);
		$pdf->Cell(20, 1, 'Codigo');

		$pdf->SetX(25);
		$pdf->Cell(20, 1, 'Descrição');

		$pdf->SetX(145);
		$pdf->Cell(20, 1, 'Estoque');

		$pdf->SetX(165);
		$pdf->Cell(20, 1, 'Estoque Min');

		$pdf->SetX(190);
		$pdf->Cell(20, 1, 'Unidade');

		$pdf->SetX(210);
		$pdf->Cell(20, 1, 'Valor Unitario');

		$pdf->SetX(240);
		$pdf->Cell(20, 1, 'Valor Total');

		$pdf->SetX(270);
		$pdf->Cell(20, 1, 'Status');
		$pdf->Line(5, 33, 290, 33); // insere linha divisória
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