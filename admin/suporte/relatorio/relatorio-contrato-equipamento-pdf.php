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
	  $titulo="Contrato com Equipamento";
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


$total = conta('estoque_equipamento_retirada',"WHERE id AND status='Baixado' AND tipo<>'3'");
$leitura = read('estoque_equipamento_retirada',"WHERE id AND status='Baixado' AND tipo<>'3' ORDER BY id_contrato ASC");

$pdf=new RELATORIO();
$pdf=new RELATORIO("L","mm","A4");
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','B',10);

$pdf->Ln(5);
$pdf->Line(5, 28, 290, 28); // insere linha divisória (Col, Lin, Col, Lin)

$pdf->SetX(5);
$pdf->Cell(20, 1, 'Id');
$pdf->SetX(20);
$pdf->Cell(20, 1, 'Nome');
$pdf->SetX(65);
$pdf->Cell(20, 1, 'Tipo de Coleta');
$pdf->SetX(102);
$pdf->Cell(20, 1, 'Quantidade');
$pdf->SetX(125);
$pdf->Cell(20, 1, 'Equipamento');
$pdf->SetX(180);
$pdf->Cell(20, 1, 'Quantidade');
$pdf->SetX(215);
$pdf->Cell(20, 1, 'Entrega');
$pdf->SetX(250);
$pdf->Cell(20, 1, 'Tipo');
$pdf->SetX(270);
$pdf->Cell(20, 1, 'Status');

$pdf->Line(5, 33, 290, 33); // insere linha divisória
$pdf->Ln(4);
$i=0;

foreach($leitura as $mostra):

	$pdf->SetFont('Arial','B',8);
	$pdf->ln();

				
	$equipamentoId = $mostra['id_equipamento'];
	$contratoId = $mostra['id_contrato'];
	$clienteId = $mostra['id_cliente'];

	$clienteId = $mostra['id_cliente'];
	$cliente = mostra('cliente',"WHERE id ='$clienteId '");

	$pdf->SetX(5);
	$pdf->Cell(10, 5, $contratoId);

	$pdf->SetX(20);
	$pdf->Cell(10, 5, substr($cliente['nome'],0,20));

	$contratoColeta = mostra('contrato_coleta',"WHERE id_contrato ='$contratoId'");
	$tipoColetaId = $contratoColeta['tipo_coleta'];
	$coleta = mostra('contrato_tipo_coleta',"WHERE id ='$tipoColetaId'");

	$pdf->SetX(65);
	$pdf->Cell(10, 5,$coleta['nome']);

	$pdf->SetX(112);
	$pdf->Cell(10, 5,$contratoColeta['quantidade']);
		
	$equipamento = mostra('estoque_equipamento',"WHERE id ='$equipamentoId'");
	$pdf->SetX(125);
	$pdf->Cell(10, 5,$equipamento['nome']);

	$pdf->SetX(186);
	$pdf->Cell(10, 5, $mostra['quantidade']);

	$pdf->SetX(215);
	$pdf->Cell(10, 5, converteData($mostra['data_entrega']));

	$pdf->SetX(250);
	if($mostra['tipo'] == '1'){
		$pdf->Cell(10, 5, 'Troca');
	}elseif($mostra['tipo'] == '2'){
		$pdf->Cell(10, 5, 'Entrega');
	}elseif($mostra['tipo'] == '3'){
		$pdf->Cell(10, 5, 'Retirada');
	}else{
		$pdf->Cell(10, 5, '-');
	} 
	
	$pdf->SetX(270);
	$pdf->Cell(10, 5, $mostra['status']);


	$i=$i+1;
	if ($i>28){
		$pdf->AddPage();   
		$pdf->SetFont('Arial','B',10);
		$pdf->Ln(5);
		
		$pdf->Line(5, 28, 290, 28); // insere linha divisória (Col, Lin, Col, Lin)
		
		$pdf->SetX(5);
		$pdf->Cell(20, 1, 'Id');
		$pdf->SetX(20);
		$pdf->Cell(20, 1, 'Nome');
		$pdf->SetX(65);
		$pdf->Cell(20, 1, 'Tipo de Coleta');
		$pdf->SetX(102);
		$pdf->Cell(20, 1, 'Quantidade');
		$pdf->SetX(125);
		$pdf->Cell(20, 1, 'Equipamento');
		$pdf->SetX(180);
		$pdf->Cell(20, 1, 'Quantidade');
		$pdf->SetX(215);
		$pdf->Cell(20, 1, 'Entrega');
		$pdf->SetX(250);
		$pdf->Cell(20, 1, 'Tipo');
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