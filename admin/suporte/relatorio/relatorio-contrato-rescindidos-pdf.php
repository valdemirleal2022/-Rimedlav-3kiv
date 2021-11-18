<?php

if(isset($_SESSION['inicio'])){
	$data1 = $_SESSION['inicio'];
}
if(isset($_SESSION['fim'])){
	$data2 = $_SESSION['fim'];
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
	  $titulo="Contratos Rescindidos";
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

$valor_total = soma('contrato',"WHERE id AND tipo='2' AND status='7' AND data_rescisao>='$data1' 
					 AND data_rescisao<='$data2' AND status='7'",'valor_mensal');
$total = conta('contrato',"WHERE id AND tipo='2' AND data_rescisao>='$data1' 
					AND data_rescisao<='$data2' AND status='7'");
$leitura = read('contrato',"WHERE id AND tipo='2' AND data_rescisao>='$data1' 
					AND data_rescisao<='$data2' AND status='7' ORDER BY data_rescisao ASC");

$pdf=new RELATORIO("L","mm","A4");
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','B',10);

$pdf->SetX(10);
$pdf->Cell(22, 1, 'Data Inicio :');
$pdf->SetX(32);
$pdf->Cell(22, 1, converteData($data1));
$pdf->SetX(70);
$pdf->Cell(22, 1, 'Data Fim :');
$pdf->SetX(88);
$pdf->Cell(22, 1, converteData($data2));

$pdf->Ln(5);
$pdf->Line(10, 28, 290, 28); // insere linha divisória (Col, Lin, Col, Lin)
$pdf->SetX(10);
$pdf->Cell(20, 1, 'Id');
$pdf->SetX(25);
$pdf->Cell(20, 1, 'Nome');
$pdf->SetX(75);
$pdf->Cell(20, 1, 'Bairro');
$pdf->SetX(100);
$pdf->Cell(20, 1, 'Consultor');
$pdf->SetX(120);
$pdf->Cell(20, 1, 'Valor Mensal');
$pdf->SetX(145);
$pdf->Cell(20, 1, 'A partir');
$pdf->SetX(165);
$pdf->Cell(10, 1, 'Motivo');
$pdf->SetX(260);
$pdf->Cell(10, 1, 'Encerramento');
$pdf->Line(10, 34, 290, 34); // insere linha divisória
$pdf->Ln(4);
$i=0;

foreach($leitura as $mostra):

	$pdf->SetFont('Arial','B',8);
	$pdf->ln();

	$pdf->SetX(10);
	$pdf->Cell(10, 5, $mostra['id']);

	$contratoId = $mostra['id'];
	$clienteId = $mostra['id_cliente'];
	$cliente = mostra('cliente',"WHERE id ='$clienteId '");
	$pdf->SetX(20);
	$pdf->Cell(10, 5, substr($cliente['nome'],0,25));

	$pdf->SetX(75);
	$pdf->Cell(10, 5, substr($cliente['bairro'],0,15));

	$pdf->SetX(100);
	$consultorId=$mostra['consultor'];
	$consultor = mostra('contrato_consultor',"WHERE id ='$consultorId'");
	$pdf->Cell(10, 5, $consultor['nome']);

	$pdf->SetX(128);
	$pdf->Cell(10, 5, converteValor($mostra['valor_mensal']));
	
	$pdf->SetX(145);
	$pdf->Cell(10, 5, converteData($mostra['data_rescisao']));

	$contratoBaixa = mostra('contrato_baixa',"WHERE id_contrato ='$contratoId' AND tipo='3' 
					ORDER BY interacao ASC");

	$pdf->SetX(165);
	$pdf->Cell(10, 5,substr($contratoBaixa['motivo'],0,20) );

	$contratoCancelamento = mostra('contrato_cancelamento',"WHERE id_contrato ='$contratoId' ORDER BY interacao ASC");
	 
	$pdf->SetX(260);
	$pdf->Cell(10, 5, converteData($contratoCancelamento['data_encerramento']) );


	$i=$i+1;
	if ($i>45){
		$pdf->AddPage();   
		$pdf->SetFont('Arial','B',10);
		$pdf->Ln(1);
		$pdf->Line(0, 25, 220, 25); // insere linha divisória (Col, Lin, Col, Lin)
	$pdf->SetX(10);
$pdf->Cell(20, 1, 'Id');
$pdf->SetX(25);
$pdf->Cell(20, 1, 'Nome');
$pdf->SetX(75);
$pdf->Cell(20, 1, 'Bairro');
$pdf->SetX(100);
$pdf->Cell(20, 1, 'Consultor');
$pdf->SetX(120);
$pdf->Cell(20, 1, 'Valor Mensal');
$pdf->SetX(145);
$pdf->Cell(20, 1, 'A partir');
$pdf->SetX(165);
$pdf->Cell(10, 1, 'Motivo');
$pdf->SetX(260);
$pdf->Cell(10, 1, 'Encerramento');
		
		$pdf->Line(0, 33, 220, 33); // insere linha divisória
		$pdf->Ln(4);~
		$i=0;
	}
endforeach;

$pdf->SetFont('Arial','B',8);
$pdf->ln(10);
$pdf->Cell(10, 5, 'Total de registros : '. $total);
$pdf->SetX(60);
$pdf->Cell(10, 5, 'Valor Total R$ : ' . converteValor($valor_total));
	

ob_clean();  
$pdf->Output('relatorio.pdf', 'I');
 
?>