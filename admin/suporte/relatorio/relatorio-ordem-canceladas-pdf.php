<?php


$data1 = $_SESSION['dataInicio'];
$data2 = $_SESSION['dataFinal'];

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
	  $titulo="Ordem Cancelada";
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

$total = conta('contrato_ordem',"WHERE data>='$data1' AND data<='$data2' 
														AND status='15'");
$leitura = read('contrato_ordem',"WHERE data>='$data1' AND data<='$data2' 
														AND status='15' ORDER BY data ASC");


$pdf=new RELATORIO();
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

$pdf->SetX(7);
$pdf->Cell(20, 1, 'Id');

$pdf->SetX(22);
$pdf->Cell(20, 1, 'Controle');

$pdf->SetX(45);
$pdf->Cell(20, 1, 'Nome');

$pdf->SetX(110);
$pdf->Cell(20, 1, 'Bairro');

$pdf->SetX(150);
$pdf->Cell(20, 1, 'Coleta');

$pdf->SetX(200);
$pdf->Cell(10, 1, 'Rota');

$pdf->SetX(225);
$pdf->Cell(10, 1, 'Data');

$pdf->SetX(250);
$pdf->Cell(10, 1, 'Interação');

$pdf->Line(5, 33, 290, 33); // insere linha divisória
$pdf->Ln(4);
$i=0;

foreach($leitura as $mostra):

	$pdf->SetFont('Arial','B',8);
	$pdf->ln();

	$pdf->SetX(7);
	$pdf->Cell(10, 5, $mostra['id'],0,0,'R');

	$contratoId = $mostra['id_contrato'];
    $contrato = mostra('contrato',"WHERE id ='$contratoId'");

	$pdf->SetX(22);
	$pdf->Cell(10, 5, substr($contrato['controle'],0,6));
	
	$clienteId = $mostra['id_cliente'];
	$cliente = mostra('cliente',"WHERE id ='$clienteId'");

	$pdf->SetX(45);
	$pdf->Cell(10, 5, substr($cliente['nome'],0,30));

	$pdf->SetX(110);
	$pdf->Cell(10, 5, substr($cliente['bairro'],0,20));

	$tipoColetaId = $mostra['tipo_coleta1'];
    $coleta = mostra('contrato_tipo_coleta',"WHERE id ='$tipoColetaId'");
	$pdf->SetX(150);
	$pdf->Cell(10, 5, $coleta['nome']);

	$rotaId = $mostra['rota'];
    $rota = mostra('contrato_rota',"WHERE id ='$rotaId'");

	$pdf->SetX(200);
	$pdf->Cell(10, 5, $rota['nome']);

	$pdf->SetX(225);
	$pdf->Cell(10, 5, converteData($mostra['data']));

	$pdf->SetX(250);
	$pdf->Cell(10, 5, date('d/m/Y H:i:s',strtotime($mostra['interacao'])));

	$i=$i+1;
	if ($i>28){
		$pdf->AddPage();   
		$pdf->SetFont('Arial','B',10);
		
		$pdf->Ln(5);
		$pdf->Line(5, 28, 290, 28); // insere linha divisória (Col, Lin, Col, Lin)
		
		$pdf->SetX(7);
		$pdf->Cell(20, 1, 'Id');

		$pdf->SetX(22);
		$pdf->Cell(20, 1, 'Controle');

		$pdf->SetX(45);
		$pdf->Cell(20, 1, 'Nome');

		$pdf->SetX(110);
		$pdf->Cell(20, 1, 'Bairro');

		$pdf->SetX(150);
		$pdf->Cell(20, 1, 'Coleta');

		$pdf->SetX(200);
		$pdf->Cell(10, 1, 'Rota');

		$pdf->SetX(225);
		$pdf->Cell(10, 1, 'Data');

		$pdf->SetX(250);
		$pdf->Cell(10, 1, 'Interação');
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